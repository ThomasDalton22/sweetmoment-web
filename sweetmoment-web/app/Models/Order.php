<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'vendor_package_id',
        'name',
        'address',
        'phone',
        'qty',
        'total_price',
        'event_date',
        'notes',
        'status',
        'transaction_id',
        'payment_status',
        'paid_at',
        'payment_data'
    ];

    protected $casts = [
        'event_date' => 'date',
        'paid_at' => 'datetime',
        'payment_data' => 'array',
        'total_price' => 'decimal:2'
    ];

    public function isPaid()
    {
        return $this->status === 'Paid' && $this->payment_status === 'success';
    }

    public function isPending()
    {
        return $this->status === 'Unpaid' || $this->payment_status === 'pending';
    }

    public function getStatusBadgeAttribute()
    {
        $classes = [
            'Paid' => 'bg-success',
            'Unpaid' => 'bg-warning',
            'Cancelled' => 'bg-danger',
            'Pending Payment' => 'bg-info'
        ];

        $class = $classes[$this->status] ?? 'bg-secondary';
        return "<span class='badge {$class}'>{$this->status}</span>";
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function vendorPackage()
    {
        return $this->belongsTo(VendorPackage::class);
    }

    public function review()
    {
        return $this->belongsTo(Review::class, 'id', 'order_id');
    }
}
