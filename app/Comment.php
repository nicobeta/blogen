<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Base
{
    protected $fillable = ['body', 'user_id'];
}
