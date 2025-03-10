<?php

declare(strict_types=1);

namespace Tests\Feature\Actions;

use App\Actions\CreateCheckout;
use App\Contracts\PaymentGatewayInterface;
use App\Exceptions\InsufficientProductException;
use App\Models\Product;
use App\Models\User;
use Mockery;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class CreateCheckoutTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->mock(PaymentGatewayInterface::class)
            ->shouldReceive('createSession')
            ->andReturn([
                'stripe_id' => 'stripe_test_id',
                'payment_url' => fake()->url()
            ]);

        $this->user = User::factory()->create();

        $this->products = Product::factory()->count(2)->create([
            'user_id' => User::factory()->asSupplier()->create()->id,
            'quantity' => 10,
            'price' => 100,
        ]);

        $this->action = $this->app->make(CreateCheckout::class);
    }

    #[Test]
    public function it_can_create_checkout_successfully()
    {
        $checkoutItems = [
            ['product_id' => $this->products[0]->id, 'quantity' => 2],
            ['product_id' => $this->products[1]->id, 'quantity' => 3],
        ];

        $result = $this->action->execute($this->user, $checkoutItems);

        $this->assertArrayHasKey('stripe_id', $result);
        $this->assertArrayHasKey('id', $result);

        $this->assertDatabaseHas('checkouts', ['user_id' => $this->user->id]);
        $this->assertDatabaseHas('orders', ['user_id' => $this->products[0]->user_id]);

        foreach ($checkoutItems as $item) {
            $this->assertDatabaseHas('order_items', ['product_id' => $item['product_id'], 'quantity' => $item['quantity']]);
        }
    }

    #[Test]
    public function it_throws_exception_when_product_stock_is_insufficient()
    {
        $checkoutItems = [
            ['product_id' => $this->products[0]->id, 'quantity' => 20],
        ];

        $this->expectException(InsufficientProductException::class);

        $this->action->execute($this->user, $checkoutItems);
    }

    public function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
