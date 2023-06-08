<?php

namespace App\Modules\Comments\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{

    const COMMENTABLE_TYPES = [
        1 => 'Only followers',
        2 => 'Only me',
        3 => 'Public',
        4 => 'Followers and friends',
        5 => 'Only the people I follow',
    ];

    protected $fillable = [
        'id',
        'user_id',
        'parent_id',
        'post_id',
        'commentable_type',
        'body'
    ];
}
