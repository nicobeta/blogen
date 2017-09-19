<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Base
{
    protected $fillable = ['body', 'user_id'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'user_id' => 'integer'
    ];
}
