<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProductImage>
 */
class ProductImageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'product_id' => Product::factory(),
            'url' => 'https://placehold.co/600x400/png'
        ];
    }

    public function product(int $product_id): static
    {
        return $this->state(fn (array $attributes) => [
            'product_id' => $product_id
        ]);
    }
}
