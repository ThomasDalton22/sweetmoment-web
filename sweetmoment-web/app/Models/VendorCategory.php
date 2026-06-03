<?php
// app/Models/VendorCategory.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VendorCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'icon'
    ];

    public function vendorProfiles()
    {
        return $this->hasMany(VendorProfile::class);
    }
}
