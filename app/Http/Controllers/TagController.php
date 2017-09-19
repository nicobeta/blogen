<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Post;
use App\Http\Resources\Tags as TagsResource;

class TagController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Post $post)
    {
        $tags = $post->all()->pluck('tags')->flatMap(function ($item) {
            return explode(',', $item);
        })->map(function ($item) {
            return trim($item);
        })->unique()->sort()->values();

        return new TagsResource($tags);
    }
}
