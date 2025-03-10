<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CheckoutResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'code' => $this->code,
            'status' => $this->status->value,
            'date_created' => $this->created_at->format('d.m.Y'),
            'price' => $this->cartPrice(),
            'orders' => OrderResource::collection($this->whenLoaded('orders'))
        ];
    }
}
