<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\CreateCheckout;
use App\Actions\VerifyCheckout;
use App\Exceptions\ResourceNotFoundException;
use App\Http\Requests\CreateCheckoutRequest;
use App\Http\Requests\VerifyCheckoutRequest;
use App\Http\Resources\CheckoutResource;
use App\Models\Checkout;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    /**
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        $checkouts = Checkout::forUser(Auth::id())
            ->with(['orders', 'orders.items', 'orders.items.product'])
            ->orderBy('id', 'desc')
            ->get();

        return CheckoutResource::collection($checkouts);
    }

    /**
     * @param CreateCheckoutRequest $request
     * @param CreateCheckout $createCheckout
     * @return \Illuminate\Http\JsonResponse|void
     */
    public function store(CreateCheckoutRequest $request, CreateCheckout $createCheckout): JsonResponse
    {
        /** @var User $user */
        $user = $request->user();
        $checkout = DB::transaction(fn () => $createCheckout->execute($user, $request->json('items')));

        return $this->success(data: $checkout, status: JsonResponse::HTTP_CREATED);
    }

    /**
     * @param VerifyCheckoutRequest $request
     * @param VerifyCheckout $verifyCheckout
     * @return JsonResponse
     */
    public function verify(VerifyCheckoutRequest $request, VerifyCheckout $verifyCheckout): JsonResponse {
        /** @var User $user */
        $user = $request->user();
        $checkout = Checkout::query()->where('code', $request->input('checkout_code'))->first();

        if (!$checkout) {
            throw new ResourceNotFoundException('Checkout not found.');
        }

        $result = $verifyCheckout->execute($user, $checkout);

        return !$result['status'] ? $this->error($result['message']) : $this->success([], $result['message']);
    }
}
