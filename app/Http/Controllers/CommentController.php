<?php

namespace App\Http\Controllers;

use App\Comment;
use App\Post;
use Illuminate\Http\Request;
use App\Http\Resources\Comment as CommentResource;
use App\Http\Resources\Comments as CommentsResource;

class CommentController extends Controller
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
        return new CommentsResource($post->comments()->paginate(10));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Post $post, Request $request)
    {
        $data = $request->validate([
            'body' => 'required'
        ]);
        $data['user_id'] = auth()->id();

        $comment = $post->comments()->create($data);

        return new CommentResource($comment);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function show(Post $post, Comment $comment)
    {
        return new CommentResource($comment);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function update(Post $post, Comment $comment, Request $request)
    {
        if (!auth()->user()->owns($comment)) {
            throw new UserUnauthorizedException;
        }

        $data = $request->validate([
            'body' => 'sometimes'
        ]);

        return new CommentResource(tap($comment)->update($data));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post, Comment $comment)
    {
        if (!auth()->user()->owns($comment)) {
            throw new UserUnauthorizedException;
        }

        $comment->delete();
        return ['success' => true];
    }
}
