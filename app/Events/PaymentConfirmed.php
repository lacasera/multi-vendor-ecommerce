<?php

declare(strict_types=1);

namespace App\Events;

use App\Models\Checkout;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PaymentConfirmed
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @param Checkout $checkout
     */
    public function __construct(public Checkout $checkout)
    {
        //
    }
}
