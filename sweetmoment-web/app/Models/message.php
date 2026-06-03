<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Support\Str;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'conversation_id',
        'from_user_id',
        'to_user_id',
        'message',
        'file',
        'is_read'
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    protected $attributes = [
        'is_read' => false
    ];

    // Relationships
    public function conversation()
    {
        return $this->belongsTo(Conversation::class);
    }

    public function fromUser()
    {
        return $this->belongsTo(User::class, 'from_user_id');
    }

    public function toUser()
    {
        return $this->belongsTo(User::class, 'to_user_id');
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

    public function scopeBetweenUsers($query, $user1Id, $user2Id)
    {
        return $query->where(function ($q) use ($user1Id, $user2Id) {
            $q->where('from_user_id', $user1Id)->where('to_user_id', $user2Id);
        })->orWhere(function ($q) use ($user1Id, $user2Id) {
            $q->where('from_user_id', $user2Id)->where('to_user_id', $user1Id);
        });
    }

    public function scopeForUser($query, $userId)
    {
        return $query->where('to_user_id', $userId)
            ->orWhere('from_user_id', $userId);
    }

    public function scopeReceivedBy($query, $userId)
    {
        return $query->where('to_user_id', $userId);
    }

    public function scopeSentBy($query, $userId)
    {
        return $query->where('from_user_id', $userId);
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

    public function getShortMessageAttribute()
    {
        return Str::limit($this->message, 100);
    }

    public function getIsFromCurrentUserAttribute()
    {
        return $this->from_user_id === auth()->id();
    }

    public function getIsToCurrentUserAttribute()
    {
        return $this->to_user_id === auth()->id();
    }

    public function getOtherUserAttribute()
    {
        if ($this->from_user_id === auth()->id()) {
            return $this->toUser;
        }
        return $this->fromUser;
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

    public function hasFile()
    {
        return !empty($this->file);
    }

    public function getFileUrl()
    {
        if ($this->hasFile()) {
            return asset('storage/' . $this->file);
        }
        return null;
    }

    public function getFileName()
    {
        if ($this->hasFile()) {
            return basename($this->file);
        }
        return null;
    }

    // Static methods
    public static function sendMessage($fromUserId, $toUserId, $message, $file = null)
    {
        return self::create([
            'from_user_id' => $fromUserId,
            'to_user_id' => $toUserId,
            'message' => $message,
            'file' => $file
        ]);
    }

    public static function getConversationBetween($user1Id, $user2Id)
    {
        return self::betweenUsers($user1Id, $user2Id)
            ->orderBy('created_at', 'asc')
            ->get();
    }

    public static function markConversationAsRead($fromUserId, $toUserId)
    {
        return self::where('from_user_id', $fromUserId)
            ->where('to_user_id', $toUserId)
            ->where('is_read', false)
            ->update(['is_read' => true]);
    }

    public static function getUnreadCountForUser($userId)
    {
        return self::where('to_user_id', $userId)
            ->where('is_read', false)
            ->count();
    }

    public static function getConversationsForUser($userId)
    {
        return self::forUser($userId)
            ->with(['fromUser', 'toUser'])
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy(function ($message) use ($userId) {
                return $message->from_user_id == $userId
                    ? $message->to_user_id
                    : $message->from_user_id;
            })
            ->map(function ($messages) {
                return $messages->first(); // Get the latest message for each conversation
            });
    }

    public static function getLastMessageBetween($user1Id, $user2Id)
    {
        return self::betweenUsers($user1Id, $user2Id)
            ->orderBy('created_at', 'desc')
            ->first();
    }

    // Boot method for model events
    protected static function boot()
    {
        parent::boot();

        // Create notification when message is sent
        static::created(function ($message) {
            // Create notification for recipient
            Notification::createForUser(
                $message->to_user_id,
                'vendor_message',
                'New Message',
                "You have a new message from {$message->fromUser->name}",
                [
                    'message_id' => $message->id,
                    'from_user_id' => $message->from_user_id,
                    'preview' => Str::limit($message->message, 50)
                ]
            );
        });

        // Auto-delete old messages (optional)
        static::creating(function ($message) {
            // Delete messages older than 90 days between the same users
            self::betweenUsers($message->from_user_id, $message->to_user_id)
                ->where('created_at', '<', Carbon::now()->subDays(90))
                ->delete();
        });
    }
}
