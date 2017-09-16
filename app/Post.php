<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Base
{
    protected $fillable = ['title', 'body', 'user_id'];

    /**
     * Convert the model instance to an array.
     *
     * @return array
     */
    public function toArray()
    {
        return [
            'title' => $this->title,
            'url' => str_slug($this->title, '-'),
            'body' => $this->body,
            'created_at' => $this->created_at
        ];
    }

    /**
     * The users that belong to the post.
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    /**
     * The comments that belongs to the post.
     */
    public function comments()
    {
        return $this->hasMany('App\Comment');
    }
}
