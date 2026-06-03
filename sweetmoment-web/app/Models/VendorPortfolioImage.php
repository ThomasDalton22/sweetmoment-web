<?php

// ============================================
// app/Models/VendorPortfolioImage.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VendorPortfolioImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'vendor_profile_id',
        'image',
        'caption',
        'is_featured'
    ];

    protected $casts = [
        'is_featured' => 'boolean'
    ];

    public function vendorProfile()
    {
        return $this->belongsTo(VendorProfile::class);
    }
}
