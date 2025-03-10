<?php

declare(strict_types=1);

namespace Tests\Feature\Actions;

use App\Actions\ReleaseProductQuantity;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ReleaseProductQuantityTest extends TestCase
{
    #[Test]
    public function it_release_product_with_held_product_quantities()
    {
        $product = Product::factory()->create([
            'quantity' => 18
        ]);

        $order = Order::factory()
            ->failed()
            ->has(OrderItem::factory([
                'product_id' => $product->id,
                'quantity' => 2
            ]), 'items')
            ->create();

        (new ReleaseProductQuantity())->execute($order);

       $this->assertEquals(20, $product->refresh()->quantity);
    }
}
