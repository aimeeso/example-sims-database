<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\ContentController as BaseContentController;
use App\Http\Resources\ContentResource;
use App\Models\Content;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ContentController extends BaseContentController
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            "pack_id" => "required|exists:packs,id",
            "name" => "required|string",
            "description" => "present|string",
            "image_path" => "nullable|string",
            "release_date" => "required|date",
            "eu_release_date" => "nullable|date",
            "console_release_date" => "required|date",
        ]);

        DB::beginTransaction();

        try {
            $content = Content::create($validated);

            DB::commit();
            return new ContentResource($content);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    public function update(Request $request, Content $content)
    {
        $validated = $request->validate([
            "pack_id" => "required|exists:packs,id",
            "name" => "required|string",
            "description" => "present|string",
            "image_path" => "nullable|string",
            "release_date" => "required|date",
            "eu_release_date" => "nullable|date",
            "console_release_date" => "required|date",
        ]);

        DB::beginTransaction();

        try {
            $content->update($validated);

            DB::commit();
            return response()->noContent();
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    public function destroy(Request $request, Content $content)
    {
        DB::beginTransaction();

        try {
            $content->delete();

            DB::commit();
            return response()->noContent();
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }
}
