<?php

namespace App\Http\Controllers;

use App\Exceptions\UserUnauthorizedException;
use App\Http\Resources\Post as PostResource;
use App\Http\Resources\Posts as PostsResource;
use App\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{

    /**
     * Instantiate a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('api.auth')->except(['index', 'show']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Post $post)
    {
        return new PostsResource($post->paginate(10));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|max:255',
            'body' => 'required'
        ]);

        $post = auth()->user()->posts()->create($data);

        return new PostResource($post);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function show(Post $post)
    {
        return new PostResource($post);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Post $post)
    {
        if (!auth()->user()->owns($post)) {
            throw new UserUnauthorizedException;
        }

        $data = $request->validate([
            'title' => 'sometimes|max:255',
            'body' => 'sometimes|email'
        ]);

        return new PostResource(tap($post)->update($data));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        if (!auth()->user()->owns($post)) {
            throw new UserUnauthorizedException;
        }

        $post->delete();
        return ['success' => true];
    }
}
