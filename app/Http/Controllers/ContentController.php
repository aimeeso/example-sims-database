<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\ContentResource;
use App\Models\Content;
use Illuminate\Http\Request;

class ContentController extends Controller
{
    //
    public function index(Request $request)
    {
        $pageSize = $request->query('pageSize');
        $packId = $request->query('packId');

        $query = Content::filterPackId($packId);

        if ($pageSize) {
            return ContentResource::collection($query->paginate($pageSize));
        } else {
            return ContentResource::collection($query->get());
        }
    }

    public function show(Request $request, Content $content)
    {
        return new ContentResource($content);
        
    }
}
