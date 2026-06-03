<?php
// app/Models/Notification.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'type',
        'title',
        'message',
        'data',
        'is_read'
    ];

    protected $casts = [
        'data' => 'array',
        'is_read' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    protected $attributes = [
        'is_read' => false
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Scopes
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    public function scopeRead($query)
    {
        return $query->where('is_read', true);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeRecent($query, $days = 7)
    {
        return $query->where('created_at', '>=', Carbon::now()->subDays($days));
    }

    // Accessors
    public function getTimeAgoAttribute()
    {
        return $this->created_at->diffForHumans();
    }

    public function getFormattedDateAttribute()
    {
        return $this->created_at->format('M d, Y \a\t g:i A');
    }

    public function getDataValueAttribute($key)
    {
        return $this->data[$key] ?? null;
    }

    // Methods
    public function markAsRead()
    {
        return $this->update(['is_read' => true]);
    }

    public function markAsUnread()
    {
        return $this->update(['is_read' => false]);
    }

    public function getIconClass()
    {
        $icons = [
            'welcome' => 'bi-hand-thumbs-up',
            'order_update' => 'bi-bag-check',
            'vendor_message' => 'bi-chat',
            'promotion' => 'bi-gift',
            'vendor_featured' => 'bi-star',
            'payment' => 'bi-credit-card',
            'review' => 'bi-star-fill',
            'cart_addition' => 'bi-cart-plus',
            'vendor_application' => 'bi-shop',
            'system' => 'bi-gear',
            'daily_welcome' => 'bi-sun'
        ];

        return $icons[$this->type] ?? 'bi-bell';
    }

    public function getColorClass()
    {
        $colors = [
            'welcome' => 'text-success',
            'order_update' => 'text-primary',
            'vendor_message' => 'text-info',
            'promotion' => 'text-warning',
            'vendor_featured' => 'text-warning',
            'payment' => 'text-success',
            'review' => 'text-warning',
            'cart_addition' => 'text-primary',
            'vendor_application' => 'text-info',
            'system' => 'text-secondary',
            'daily_welcome' => 'text-primary'
        ];

        return $colors[$this->type] ?? 'text-primary';
    }

    // Static methods
    public static function createForUser($userId, $type, $title, $message, $data = null)
    {
        return self::create([
            'user_id' => $userId,
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'data' => $data
        ]);
    }

    public static function markAllAsReadForUser($userId)
    {
        return self::where('user_id', $userId)
            ->where('is_read', false)
            ->update(['is_read' => true]);
    }

    public static function getUnreadCountForUser($userId)
    {
        return self::where('user_id', $userId)
            ->where('is_read', false)
            ->count();
    }

    public static function getRecentForUser($userId, $limit = 10)
    {
        return self::where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    // Boot method for model events
    protected static function boot()
    {
        parent::boot();

        // Auto-delete old notifications (optional)
        static::creating(function ($notification) {
            // Delete notifications older than 30 days for the same user
            self::where('user_id', $notification->user_id)
                ->where('created_at', '<', Carbon::now()->subDays(30))
                ->delete();
        });
    }
}
