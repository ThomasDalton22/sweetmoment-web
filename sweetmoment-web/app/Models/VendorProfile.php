<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VendorProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'vendor_category_id',
        'business_name',
        'description',
        'price_range_min',
        'price_range_max',
        'location',
        'phone',
        'whatsapp',
        'instagram',
        'website',
        'rating',
        'total_reviews',
        'is_verified',
        'is_featured',
        'status',
        'search_tags'
    ];

    protected $casts = [
        'price_range_min' => 'decimal:2',
        'price_range_max' => 'decimal:2',
        'rating' => 'decimal:2',
        'is_verified' => 'boolean',
        'is_featured' => 'boolean'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(VendorCategory::class, 'vendor_category_id');
    }

    public function packages()
    {
        return $this->hasMany(VendorPackage::class);
    }

    public function portfolioImages()
    {
        return $this->hasMany(VendorPortfolioImage::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function availability()
    {
        return $this->hasMany(VendorAvailability::class);
    }

    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }

    public function vendorCategory()
    {
        return $this->belongsTo(VendorCategory::class, 'vendor_category_id');
    }
}
