<?php

declare(strict_types=1);

namespace Tests\Feature\Controllers\CheckoutController;

use App\Contracts\PaymentGatewayInterface;
use App\Enums\PaymentStatus;
use App\Events\PaymentConfirmed;
use App\Models\Checkout;
use App\Models\User;
use Illuminate\Support\Facades\Event;
use Mockery;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class CheckoutVerifyTest extends TestCase
{
    protected User $user;
    protected User $anotherUser;

    protected Checkout $checkout;
    protected $mockPaymentGateway;

    protected string $endpoint = "/api/checkouts/verify";

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->anotherUser = User::factory()->create();

        $this->checkout = Checkout::factory()->create([
            'user_id' => $this->user->id,
            'code' => 'CHK123',
            'status' => PaymentStatus::PENDING->value,
            'external_payment_id' => 'stripe_session_123',
        ]);

        $this->mock(PaymentGatewayInterface::class)
            ->shouldReceive('getCheckoutSession')
            ->andReturn(PaymentStatus::PAID->value);
    }

    #[Test]
    public function user_can_successfully_verify_a_paid_checkout()
    {
        Event::fake();

        $this->actingAs($this->user)
            ->postJson($this->endpoint, ['checkout_code' => $this->checkout->code])
            ->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Order confirmed',
            ]);

        $this->assertDatabaseHas('checkouts', [
            'id' => $this->checkout->id,
            'status' => PaymentStatus::PAID->value,
        ]);

        Event::assertDispatched(PaymentConfirmed::class);
    }

    #[Test]
    public function it_returns_404_if_checkout_does_not_exist()
    {
        $this->actingAs($this->user)
            ->postJson($this->endpoint, ['checkout_code' => 'INVALID_CODE'])
            ->assertStatus(404)
            ->assertJson([
                'success' => false,
                'message' => 'Checkout not found.',
            ]);
    }

    #[Test]
    public function it_returns_403_if_user_tries_to_verify_someone_elses_checkout()
    {
        $this->actingAs($this->anotherUser)
            ->postJson($this->endpoint, ['checkout_code' => $this->checkout->code])
            ->assertStatus(403)
            ->assertJson([
                'success' => false,
                'message' => 'You do not have permission to verify this checkout.',
            ]);
    }

    #[Test]
    public function it_returns_200_if_checkout_is_already_paid()
    {
        $this->checkout->update(['status' => PaymentStatus::PAID->value]);

        $this->actingAs($this->user)
            ->postJson($this->endpoint, ['checkout_code' => $this->checkout->code])
            ->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Order already confirmed',
            ]);
    }

    #[Test]
    public function it_returns_400_if_payment_verification_fails()
    {
        $this->mock(PaymentGatewayInterface::class)
            ->shouldReceive('getCheckoutSession')
            ->andReturn(PaymentStatus::PENDING->value);

        $this->checkout->update(['status' => PaymentStatus::PENDING->value]);
        $this->actingAs($this->user)
            ->postJson($this->endpoint, ['checkout_code' => $this->checkout->code])
            ->assertStatus(400)
            ->assertJson([
                'success' => false,
                'message' => 'Unable to confirm order payment',
            ]);

        $this->assertDatabaseHas('checkouts', [
            'id' => $this->checkout->id,
            'status' => PaymentStatus::FAILED->value,
        ]);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
