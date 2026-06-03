<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Testimony extends Model
{
    protected $fillable = ['user', 'testimony', 'rating'];

    protected $casts = [
        'rating' => 'integer'
    ];
}
