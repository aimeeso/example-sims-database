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
        $filter = $request->only(['packId']);

        $query = Content::filterPackId(isset($filter['packId']) ? $filter['packId'] : null);

        if ($pageSize) {
            return ContentResource::collection($query->paginate($pageSize));
        } else {
            return ContentResource::collection($query->get());
        }
    }
}
