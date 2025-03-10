<?php

declare(strict_types=1);

namespace Tests\Feature\Actions;

use App\Actions\UpdateCheckoutOrdersStatus;
use App\Enums\PaymentStatus;
use App\Models\Checkout;
use App\Models\Order;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class UpdateCheckoutOrderStatusTest extends TestCase
{
    #[Test]
    public function it_can_update_status_of_all_orders_associated_with_a_checkout_as_paid()
    {
        $checkout = Checkout::factory()->create();
        $orders = Order::factory()
            ->count(3)
            ->create(['checkout_id' => $checkout->id]);

        (new UpdateCheckoutOrdersStatus())->execute($checkout, PaymentStatus::PAID);

        foreach ($orders as $order) {
            $order->refresh();

            $this->assertEquals($checkout->id, $order->checkout_id);
            $this->assertEquals(PaymentStatus::PAID, $order->status);
        }
    }

    #[Test]
    public function it_can_update_status_of_all_orders_associated_with_a_checkout_as_failed()
    {
        $checkout = Checkout::factory()->create();
        $orders = Order::factory()
            ->count(3)
            ->create(['checkout_id' => $checkout->id]);

        (new UpdateCheckoutOrdersStatus())->execute($checkout, PaymentStatus::FAILED);

        foreach ($orders as $order) {
            $order->refresh();

            $this->assertEquals($checkout->id, $order->checkout_id);
            $this->assertEquals(PaymentStatus::FAILED, $order->status);
        }
    }

    #[Test]
    public function it_will_not_update_status_of_orders_not_associated_with_the_checkout()
    {
        $checkout = Checkout::factory()->create();
        $orders = Order::factory()
            ->count(3)
            ->create(['checkout_id' => $checkout->id]);
        $nonAssociatedOrders = Order::factory()->count(2)->create();

        (new UpdateCheckoutOrdersStatus())->execute($checkout, PaymentStatus::PAID);

        foreach ($orders as $order) {
            $order->refresh();

            $this->assertEquals($checkout->id, $order->checkout_id);
            $this->assertEquals(PaymentStatus::PAID, $order->status);
        }

        foreach ($nonAssociatedOrders as $nonAssociatedOrder) {
            $nonAssociatedOrder->refresh();

            $this->assertNotEquals($checkout->id, $nonAssociatedOrder->checkout_id);
            $this->assertEquals(PaymentStatus::PENDING, $nonAssociatedOrder->status);
        }
    }
}
