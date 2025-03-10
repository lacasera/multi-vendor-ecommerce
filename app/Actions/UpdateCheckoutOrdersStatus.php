<?php

declare(strict_types=1);

namespace App\Actions;

use App\Enums\PaymentStatus;
use App\Models\Checkout;
use App\Models\Order;

class UpdateCheckoutOrdersStatus
{
    public function execute(Checkout $checkout, PaymentStatus $status): void
    {
        Order::query()
            ->where('checkout_id', $checkout->id)
            ->update(['status' => $status]);
    }
}
