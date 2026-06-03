<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class vendor_offers extends Model
{
    protected $fillable = [
        'jenispenawaran', 
        'budget', 
        'catatan', 
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);  // Wedding belongs to User
    }
}
