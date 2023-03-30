<?php

namespace App\Mail\Auth;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TwoFactorCodeMail extends Mailable
{
    use Queueable, SerializesModels;
    public string $code = '';
    public $user = null;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(string $code, $user)
    {
        //
        $this->code = $code;
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('[Sims Database] Two-Factor Authentication')
            ->markdown('auth.two-factor-code', [
                'code' => $this->code,
                'user' => $this->user,
            ]);
    }
}
