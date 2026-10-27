<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'organizer_id',
        'category_id',
        'venue_id',
        'artist_id',
        'title',
        'slug',
        'description',
        'poster_image',
        'event_date',
        'event_type',
        'status',
        'admin_rejection_reason',
    ];

    protected function casts(): array
    {
        return [
            'event_date' => 'datetime',
        ];
    }

    public function organizer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'organizer_id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function venue(): BelongsTo
    {
        return $this->belongsTo(Venue::class);
    }

    public function artist(): BelongsTo
    {
        return $this->belongsTo(Artist::class);
    }

    public function ticketTypes(): HasMany
    {
        return $this->hasMany(TicketType::class);
    }

    public function seats(): HasMany
    {
        return $this->hasMany(Seat::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function isStanding(): bool
    {
        return $this->event_type === 'standing';
    }

    public function isSeated(): bool
    {
        return $this->event_type === 'seated';
    }

    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    public function hasSoldTickets(): bool
    {
        return $this->orders()->where('status', 'paid')->exists();
    }

    public function isPast(): bool
    {
        return $this->event_date ? $this->event_date->isPast() : false;
    }

    public function isEnded(): bool
    {
        return $this->status === 'ended' || $this->isPast();
    }

    public function isBookingClosed(): bool
    {
        if (! $this->event_date) {
            return false;
        }

        // Đóng cổng bán vé trước giờ diễn 1 ngày (24 giờ) hoặc sự kiện đã kết thúc
        return now()->greaterThanOrEqualTo($this->event_date->copy()->subDay());
    }

    public function canBeBooked(): bool
    {
        return $this->isApproved() && ! $this->isBookingClosed();
    }

    public function getPosterUrlAttribute(): string
    {
        if (empty($this->poster_image)) {
            return 'https://images.unsplash.com/photo-1470225620780-dba8ba36b745?auto=format&fit=crop&w=1200&q=80';
        }

        if (str_starts_with($this->poster_image, 'http://') || str_starts_with($this->poster_image, 'https://')) {
            return $this->poster_image;
        }

        return asset('storage/'.$this->poster_image);
    }
}
