<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\TwoFactorRequest;
use App\Http\Resources\Auth\UserProfileResource;
use App\Mail\Auth\TwoFactorCodeMail;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{   
    protected $authGuard;
    
    public function login(LoginRequest $request)
    {
        $request->authenticate($this->authGuard);

        $user = User::where('email', $request->input('email'))->first();


        if ($user->tfa_enabled && $this->authGuard == 'user') { // 2fa is enabled
            $this->generate2faCode($user);
            return $this->respondWithToken($user, ['2fa']);
        } else {
            $this->updateLoginTime($user);
            $user->currentAccessToken()?->delete(); // prevent muti-login
            return $this->respondWithToken($user, [$this->authGuard]);
        }
    }

    public function logout(Request $request)
    {
        $request->user($this->authGuard)->currentAccessToken()->delete();
        return response()->noContent();
    }

    public function refresh(Request $request)
    {
        return $this->respondWithToken($request->user($this->authGuard), [$this->authGuard]);
    }

    public function profile(Request $request)
    {
        $user = $request->user($this->authGuard);
        return new UserProfileResource($user);
    }

    public function verify2fa(TwoFactorRequest $request)
    {
        $user = $request->user($this->authGuard);
        $now = Carbon::now();
        // the code is expired
        if (Carbon::parse($user->tfa_expired_at)->lessThan($now)) {
            return new JsonResponse(["message" => "2fa is expired."], 400);
        }

        // the code is correct
        if ($user->tfa_code === $request->input('code') || !config('auth.tfa.enabled')) {

            $user->currentAccessToken()->delete();
            $this->updateLoginTime($user);
            return $this->respondWithToken($user, [$this->authGuard]);
        }

        return new JsonResponse(["message" => "incorrect code."], 400);
    }

    public function regenerate2fa(Request $request)
    {
        $user = $request->user($this->authGuard);
        $this->generate2faCode($user);

        return response()->noContent();
    }

    public function disable2fa(Request $request)
    {
        $user = $request->user($this->authGuard);
        $user->tfa_enabled = false;
        $user->save();

        return response()->noContent();
    }

    public function enable2fa(Request $request)
    {

        $user = $request->user($this->authGuard);
        $user->tfa_enabled = true;
        $user->save();

        return response()->noContent();
    }

    /**
     * Get the token array structure.
     *
     * @param  User $user
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function generate2faCode(User $user)
    {
        $code = random_int(100000, 999999);
        $expired = (Carbon::now())->addSeconds(config('auth.tfa.timeout'));

        $user->tfa_code = (string)$code;
        $user->tfa_expired_at = $expired;
        $user->save();

        // notify the user about the code and expired
        Mail::to($user)->send(new TwoFactorCodeMail((string)$code, $user));
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($user, array $ablilities)
    {
        $token = $user->createToken($this->authGuard, $ablilities);
        return response()->json([
            'access_token' => $token->plainTextToken,
            'ability' => $ablilities[0],
            'token_type' => 'bearer',
            'expires_in' => config('sanctum.expiration'),
        ]);
    }
}
