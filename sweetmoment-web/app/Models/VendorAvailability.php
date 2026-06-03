<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VendorAvailability extends Model
{
    use HasFactory;

    protected $fillable = [
        'vendor_profile_id',
        'date',
        'is_available',
        'notes'
    ];

    protected $casts = [
        'date' => 'date',
        'is_available' => 'boolean'
    ];

    public function vendorProfile()
    {
        return $this->belongsTo(VendorProfile::class);
    }
}
