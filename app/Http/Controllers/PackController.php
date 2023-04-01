<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\PackResource;
use App\Models\Pack;
use Illuminate\Http\Request;

class PackController extends Controller
{
    //
    public function index(Request $request)
    {
        $pageSize = $request->query('pageSize');

        if ($pageSize) {
            return PackResource::collection(Pack::paginate($pageSize));
        } else {
            return PackResource::collection(Pack::all());
        }
    }
}
