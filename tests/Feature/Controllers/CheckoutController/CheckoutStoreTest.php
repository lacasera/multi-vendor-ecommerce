<?php

declare(strict_types=1);

namespace Tests\Feature\Controllers\CheckoutController;

use App\Contracts\PaymentGatewayInterface;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Sequence;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class CheckoutStoreTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->mock(PaymentGatewayInterface::class)
            ->shouldReceive('createSession')
            ->andReturn(['stripe_id' => 'test_id']);
    }

    #[Test]
    public function user_should_be_able_to_create_checkout()
    {
        $supplierOne = User::factory()->asSupplier()->create();
        $supplierTwo = User::factory()->asSupplier()->create();
        $user = User::factory()->create();

        $products = Product::factory()
            ->count(3)
            ->state(new Sequence(
                fn (Sequence $sequence) => ['user_id' => $supplierOne],
                fn (Sequence $sequence) => ['user_id' => $supplierTwo],
            ))
            ->create(['quantity' => 10])
            ->map(fn (Product $product) => [
                'product_id' => $product->id,
                'quantity' => random_int(1, 5),
            ])->all();

        $this->actingAs($user)
            ->postJson('api/checkouts', ['items' => $products])
            ->assertStatus(201)
            ->assertJson([
                'success' => true,
                'message' => 'success',
                'data' => [
                    'stripe_id' => 'test_id',
                ]
            ]);
    }
}
