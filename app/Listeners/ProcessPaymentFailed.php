<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Actions\ReleaseProductQuantity;
use App\Actions\UpdateCheckoutOrdersStatus;
use App\Enums\PaymentStatus;
use App\Events\PaymentFailed;
use Illuminate\Support\Facades\DB;

class ProcessPaymentFailed
{
    /**
     * Create the event listener.
     *
     * @param ReleaseProductQuantity $releaseProductQuantity
     * @param UpdateCheckoutOrdersStatus $updateCheckoutOrdersStatus
     */
    public function __construct(
        protected ReleaseProductQuantity $releaseProductQuantity,
        protected UpdateCheckoutOrdersStatus $updateCheckoutOrdersStatus
    ) {
    }

    /**
     * Handle the event.
     */
    public function handle(PaymentFailed $event): void
    {
        DB::transaction(function () use ($event) {
            $orders = $event->checkout->orders;

            foreach ($orders as $order) {
                $this->releaseProductQuantity->execute($order);
            }

            $this->updateCheckoutOrdersStatus->execute($event->checkout, PaymentStatus::FAILED);
        });
    }
}
