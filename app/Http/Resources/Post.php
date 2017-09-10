<?php

namespace App\Http\Resources;

class Post extends Core\Item
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'title' => $this->title,
            'url' => str_slug($this->title, '-'),
            'body' => $this->body,
            'created_at' => $this->created_at
        ];
    }
}
