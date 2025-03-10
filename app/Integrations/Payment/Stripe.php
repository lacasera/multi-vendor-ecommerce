<?php

declare(strict_types=1);

namespace App\Integrations\Payment;

use App\Contracts\PaymentGatewayInterface;
use App\Models\Checkout;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;

class Stripe implements PaymentGatewayInterface
{
    protected PendingRequest $stripeApi;

    public function __construct()
    {
        $this->stripeApi = Http::asForm()
            ->baseUrl(config('stripe.base_url'))
            ->withToken(config('stripe.secret_key'));
    }

    /**
     * @param Checkout $checkout
     * @return array|null
     * @throws \Illuminate\Http\Client\ConnectionException
     */
    public function createSession(Checkout $checkout): ?array
    {
        $response = $this->stripeApi->post("/checkout/sessions", $checkout->getPaymentCheckoutPayload());

        if (!$response->ok()) {
            return null;
        }

        return [
            'stripe_id' => $response->json('id'),
            'payment_url' => $response->json('url')
        ];
    }

    /**
     * @param string $checkoutId
     * @return string|null
     * @throws \Illuminate\Http\Client\ConnectionException
     */
    public function getCheckoutSession(string $checkoutId): ?string
    {
        $response =  $this->stripeApi->get("/checkout/sessions/$checkoutId");

        if (!$response->ok()) {
            return null;
        }

        return $response->json('payment_status');
    }
}
