<?php

declare(strict_types=1);

namespace App\Contracts;

use App\Models\Checkout;

interface PaymentGatewayInterface
{
    public function createSession(Checkout $checkout): ?array;

    public function getCheckoutSession(string $checkoutId): ?string;
}
