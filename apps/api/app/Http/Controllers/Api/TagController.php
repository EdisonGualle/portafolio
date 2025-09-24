<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Tag;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class TagController extends Controller
{
    use ApiResponse;

    public function index(Request $request)
    {
        $tags = Tag::query()
            ->select(['id', 'name', 'slug'])
            ->orderBy('name')
            ->get();

        return $this->successResponse($tags, 'Tags obtenidos correctamente');
    }
}
