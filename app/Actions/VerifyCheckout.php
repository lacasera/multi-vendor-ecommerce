<?php

declare(strict_types=1);

namespace App\Actions;

use App\Contracts\PaymentGatewayInterface;
use App\Enums\PaymentStatus;
use App\Events\PaymentConfirmed;
use App\Events\PaymentFailed;
use App\Exceptions\PaymentFailedException;
use App\Exceptions\PermissionDenied;
use App\Exceptions\ResourceNotFoundException;
use App\Models\Checkout;
use App\Models\User;

class VerifyCheckout
{
    public function __construct(protected PaymentGatewayInterface $paymentGateway)
    {
    }

    /**
     * @param User $user
     * @param Checkout $checkoutCode
     * @return array
     * @throws PaymentFailedException
     * @throws PermissionDenied
     * @throws ResourceNotFoundException
     */
    public function execute(User $user, Checkout $checkout): array
    {
        if ($checkout->user_id !== $user->id) {
            throw new PermissionDenied('You do not have permission to verify this checkout.');
        }

        if ($checkout->status->isFailed()) {
            throw new PaymentFailedException('Payment already failed.');
        }

        if ($checkout->status->isPaid()) {
            return $this->response(true, 'Order already confirmed');
        }

        $paymentStatus = $this->paymentGateway->getCheckoutSession($checkout->external_payment_id);

        if ($paymentStatus !== PaymentStatus::PAID->value) {
            $checkout->update(['status' => PaymentStatus::FAILED->value]);

            PaymentFailed::dispatch($checkout);

            throw new PaymentFailedException('Unable to confirm order payment');
        }

        $checkout->update(['status' => PaymentStatus::PAID->value]);

        PaymentConfirmed::dispatch($checkout);

        return $this->response(true, 'Order confirmed');
    }

    /**
     * Standardized response format.
     * @param bool $status
     * @param string $message
     * @return array
     */
    private function response(bool $status, string $message): array
    {
        return [
            'status' => $status,
            'message' => $message,
        ];
    }
}
