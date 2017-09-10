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
}
