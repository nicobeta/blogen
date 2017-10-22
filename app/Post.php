<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Base
{
    protected $fillable = ['title', 'body', 'user_id', 'category_id', 'tags', 'image'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'user_id' => 'integer'
    ];

    /**
     * Convert the model instance to an array.
     *
     * @return array
     */
    public function toArray()
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'url' => str_slug($this->title, '-'),
            'body' => $this->body,
            'user_id' => $this->user_id,
            'category_id' => $this->category_id,
            'tags' => $this->tags,
            'image' => $this->image,
            'created_at' => $this->created_at
        ];
    }

    /**
     * The users that the post belongs to.
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    /**
     * The comments that the post has.
     */
    public function comments()
    {
        return $this->hasMany('App\Comment');
    }

    /**
     * The category of the post.
     */
    public function category()
    {
        return $this->hasOne('App\Category');
    }
}
