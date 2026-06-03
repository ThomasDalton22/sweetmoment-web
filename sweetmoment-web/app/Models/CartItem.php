<?php

// ============================================
// app/Models/CartItem.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'vendor_package_id',
        'quantity',
        'event_date',
        'notes'
    ];

    protected $casts = [
        'event_date' => 'date'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function vendorPackage()
    {
        return $this->belongsTo(VendorPackage::class);
    }
}
