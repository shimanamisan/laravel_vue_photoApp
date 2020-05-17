<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    // JSON煮含める属性
    protected $visible = [
        'author', 'content'
    ];

    public function author()
    {
        return $this->belongsTo('App\User', 'user_id', 'id', 'users');
    }
}
