<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\PackController as BasePackController;
use App\Http\Resources\PackResource;
use App\Models\Pack;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PackController extends BasePackController
{
    //
    public function store(Request $request)
    {
        $validated = $request->validate([
            "name" => "required|string"
        ]);

        DB::beginTransaction();

        try {
            $pack = Pack::create($validated);

            DB::commit();
            return new PackResource($pack);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    public function update(Request $request, Pack $pack)
    {
        $validated = $request->validate([
            "name" => "required|string"
        ]);

        DB::beginTransaction();

        try {
            $pack->update($validated);

            DB::commit();
            return response()->noContent();
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    public function destroy(Request $request, Pack $pack)
    {
        // Cannot delete if related to any contents
        if ($pack->contents()->count() > 0) {
            return response()->json(['message' => "The pack is related to some contents."], 400);
        }

        DB::beginTransaction();

        try {
            $pack->delete();

            DB::commit();
            return response()->noContent();
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }
}
