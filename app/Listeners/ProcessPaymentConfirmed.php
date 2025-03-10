<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Actions\UpdateCheckoutOrdersStatus;
use App\Enums\PaymentStatus;
use App\Events\PaymentConfirmed;

class ProcessPaymentConfirmed
{
    /**
     * Create the event listener.
     *
     * @param UpdateCheckoutOrdersStatus $updateCheckoutOrdersStatus
     */
    public function __construct(protected UpdateCheckoutOrdersStatus $updateCheckoutOrdersStatus)
    {
    }

    /**
     * Handle the event.
     */
    public function handle(PaymentConfirmed $event): void
    {
        $this->updateCheckoutOrdersStatus->execute($event->checkout, PaymentStatus::PAID);
    }
}
