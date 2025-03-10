<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class ReleaseProductQuantity
{
    public function execute(Order $order): void
    {
        DB::transaction(function () use ($order) {
            $order->items->each(fn (OrderItem $item) => Product::query()
                ->select('id, quantity')
                ->lockForUpdate()
                ->increment('quantity', $item->quantity)
            );
        });
    }
}
