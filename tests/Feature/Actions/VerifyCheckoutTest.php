<?php

declare(strict_types=1);

namespace Tests\Feature\Actions;

use App\Actions\VerifyCheckout;
use App\Contracts\PaymentGatewayInterface;
use App\Enums\PaymentStatus;
use App\Events\PaymentConfirmed;
use App\Events\PaymentFailed;
use App\Exceptions\PaymentFailedException;
use App\Exceptions\PermissionDenied;
use App\Models\Checkout;
use App\Models\User;
use Illuminate\Support\Facades\Event;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class VerifyCheckoutTest extends TestCase
{
    #[Test]
    public function it_will_throw_permission_denied_exception_when_given_user_is_different_from_checkout_user(): void
    {
        $user = User::factory()->create();
        $checkout = Checkout::factory()->create();

        $this->expectException(PermissionDenied::class);
        $this->expectExceptionMessage('You do not have permission to verify this checkout.');

        app(VerifyCheckout::class)->execute($user, $checkout);
    }

    #[Test]
    public function it_will_throw_payment_failed_exception_when_checkout_has_already_failed(): void
    {
        $user = User::factory()->create();
        $checkout = Checkout::factory()->create([
            'user_id' => $user,
            'status' => PaymentStatus::FAILED,
        ]);

        $this->expectException(PaymentFailedException::class);
        $this->expectExceptionMessage('Payment already failed.');

        app(VerifyCheckout::class)->execute($user, $checkout);
    }

    #[Test]
    public function it_will_return_successful_response_when_checkout_is_already_paid(): void
    {
        $user = User::factory()->create();
        $checkout = Checkout::factory()->create([
            'user_id' => $user,
            'status' => PaymentStatus::PAID,
        ]);

        $response = app(VerifyCheckout::class)->execute($user, $checkout);

        $this->assertTrue($response['status']);
        $this->assertEquals('Order already confirmed', $response['message']);
    }

    #[Test]
    public function it_will_throw_payment_failed_exception_when_payment_gateway_failed(): void
    {
        Event::fake();

        $user = User::factory()->create();
        $checkout = Checkout::factory()->create([
            'user_id' => $user,
            'status' => PaymentStatus::PENDING,
            'external_payment_id' => 'test-payment-id',
        ]);

        $this->mock(PaymentGatewayInterface::class)
            ->shouldReceive('getCheckoutSession')
            ->withArgs([$checkout->external_payment_id])
            ->andReturn('failed');

        $this->expectException(PaymentFailedException::class);
        $this->expectExceptionMessage('Unable to confirm order payment');

        app(VerifyCheckout::class)->execute($user, $checkout);

        Event::assertDispatched(PaymentFailed::class);
        $this->assertEquals(PaymentStatus::FAILED, $checkout->fresh()->status);
    }

    #[Test]
    public function it_will_return_successful_when_payment_gateway_is_successful(): void
    {
        Event::fake();

        $user = User::factory()->create();
        $checkout = Checkout::factory()->create([
            'user_id' => $user,
            'status' => PaymentStatus::PENDING,
            'external_payment_id' => 'test-payment-id',
        ]);

        $this->mock(PaymentGatewayInterface::class)
            ->shouldReceive('getCheckoutSession')
            ->withArgs([$checkout->external_payment_id])
            ->andReturn('paid');

        $response = app(VerifyCheckout::class)->execute($user, $checkout);

        Event::assertDispatched(PaymentConfirmed::class);
        $this->assertEquals(PaymentStatus::PAID, $checkout->fresh()->status);

        $this->assertTrue($response['status']);
        $this->assertEquals('Order confirmed', $response['message']);
    }
}
