<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\PaymentStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Checkout extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'status' => PaymentStatus::class,
        'created_at' => 'datetime',
    ];

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeForUser(Builder $query, int $userId): Builder
    {
        return $query->where('user_id', $userId);
    }

    public function getPaymentCheckoutPayload(): array
    {
        $payload = [
            'mode' => 'payment',
            'allow_promotion_codes' => 'true',
            'customer_email' => $this->user->email,
            'invoice_creation' => [
                'enabled' => 'true',
            ],
            'success_url' => url('/verify', [
                'checkout' => $this->code,
            ]),
            'cancel_url' => url('/verify', [
                'checkout' => $this->code,
            ]),
        ];

        $orderIds = $this->orders->pluck('id')->toArray();

        $lineItems = OrderItem::with('product')->whereIn('order_id', $orderIds)
            ->get()
            ->map(fn(OrderItem $orderItem) => [
                'price_data' => [
                    'currency' => 'usd',
                    'product_data' => [
                        'name' => $orderItem->product->title
                    ],
                    'unit_amount' => $orderItem->product->price * 100
                ],
                'quantity' => $orderItem->quantity
            ]);

        $payload['line_items'] = $lineItems->toArray();

       return $payload;
    }

    public function cartPrice()
    {
        $orderIds = $this->orders->pluck('id')->toArray();

        return OrderItem::query()
            ->whereIn('order_id', $orderIds)
            ->sum('price');
    }
}
