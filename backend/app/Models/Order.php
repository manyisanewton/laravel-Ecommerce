<?php

namespace App\Models;

use App\Enums\OrderStatus;
use App\Enums\PaymentProvider;
use App\Enums\PaymentStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'reference',
        'status',
        'payment_provider',
        'payment_status',
        'payment_reference',
        'total_amount',
        'meta',
    ];

    protected function casts(): array
    {
        return [
            'status' => OrderStatus::class,
            'payment_provider' => PaymentProvider::class,
            'payment_status' => PaymentStatus::class,
            'total_amount' => 'decimal:2',
            'meta' => 'array',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
}
