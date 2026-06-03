<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    protected $fillable = [
        'title',
        'description',
        'content',
        'image',
        'likes',
        'dislikes',
        'reaction'
    ];

    protected $casts = [
        'likes' => 'integer',
        'dislikes' => 'integer'
    ];
}
