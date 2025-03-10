<?php

declare(strict_types=1);

namespace App\Actions;

use App\Contracts\PaymentGatewayInterface;
use App\Exceptions\InsufficientProductException;
use App\Exceptions\UnableToCreateStripeCheckout;
use App\Models\Checkout;
use App\Models\Product;
use App\Models\User;
use Hashids\Hashids;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

class CreateCheckout
{
    public function __construct(protected PaymentGatewayInterface $paymentGateway)
    {
    }

    /**
     * @param User $user
     * @param array $checkoutItems
     * @return array
     * @throws InsufficientProductException
     * @throws UnableToCreateStripeCheckout
     */
    public function execute(User $user, array $checkoutItems): array
    {
        $products = $this->fetchProducts($checkoutItems);

        $this->validateStockAvailability($checkoutItems, $products);

        $checkout = $this->createCheckout($user);

        $this->createOrdersForSuppliers($checkout, $checkoutItems, $products);

        return $this->initializePaymentSession($checkout);
    }

    /**
     * @param array $checkoutItems
     * @return Collection
     */
    private function fetchProducts(array $checkoutItems): Collection
    {
        $productIds = Arr::pluck($checkoutItems, 'product_id');

        return Product::query()
            ->whereIn('id', $productIds)
            ->lockForUpdate()
            ->get();
    }

    /**
     * @param array $checkoutItems
     * @param Collection $groupedProducts
     * @return void
     * @throws InsufficientProductException
     */
    private function validateStockAvailability(array $checkoutItems, Collection $products): void
    {
        foreach ($checkoutItems as  $item) {
            $product = $products->firstWhere('id', $item['product_id']);

            if ($product && $item['quantity'] > $product->quantity) {
                throw new InsufficientProductException("Insufficient products in cart");
            }
        }
    }

    /**
     * @param User $user
     * @return Checkout
     */
    private function createCheckout(User $user): Checkout
    {
        return Checkout::create([
            'user_id' => $user->id,
            'code' => $this->generateUniqueCode()
        ]);
    }

    /**
     * @param Checkout $checkout
     * @param array $checkoutItems
     * @param Collection $products
     * @return void
     */
    private function createOrdersForSuppliers(Checkout $checkout, array $checkoutItems, Collection $products): void
    {
        $groupedProducts = $products->groupBy('user_id');

        foreach ($groupedProducts as $supplierProducts) {
            $orderTotal = 0;
            $supplierId = $supplierProducts->pluck('user_id')->unique()->first();
            $order = $checkout->orders()->create(['user_id' => $supplierId]);

            foreach ($supplierProducts as $product) {
                $selectedItem = collect($checkoutItems)->firstWhere('product_id', $product->id);

                if ($selectedItem) {
                    $orderTotal += $selectedItem['quantity'] * $product->price;
                    $order->items()->create([...$selectedItem, 'price' => $product->price]);

                    $product->decrement('quantity', $selectedItem['quantity']);
                }
            }

            $order->update(['total' => $orderTotal]);
        }
    }

    /**
     * @param Checkout $checkout
     * @return array
     * @throws UnableToCreateStripeCheckout
     */
    private function initializePaymentSession(Checkout $checkout): array
    {
        $stripeSession = $this->paymentGateway->createSession($checkout);

        if (!$stripeSession) {
            throw new UnableToCreateStripeCheckout("Unable to create Stripe checkout");
        }

        $checkout->update(['external_payment_id' => $stripeSession['stripe_id']]);

        return array_merge($stripeSession, ['id' => $checkout->refresh()->id]);
    }

    /**
     * @return string
     */
    private function generateUniqueCode(): string
    {
        return (new Hashids(config('app.key'), 6))->encode(time());
    }
}
