<?php

declare(strict_types=1);

namespace Tests\Feature\Controllers\ProductController;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Sequence;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ProductControllerTest extends TestCase
{
    protected string $endpoint = "api/products";

    #[Test]
    public function it_can_list_products()
    {
        Product::factory()->count(3)->create();

        $this->getJson($this->endpoint)
            ->assertOk()
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'title',
                        'slug',
                        'price',
                        'description',
                        'sku',
                        'images',
                        'attributes'
                    ]
                ]
            ]);
    }

    #[Test]
    public function it_can_filter_by_price_range()
    {
        Product::factory()
            ->count(4)
            ->state(new Sequence(
                ['price' => 100],
                ['price' => 200],
                ['price' => 250],
                ['price' => 300],
            ))->create();

        $params = [
            'min_price' => 200,
            'max_price' => 260
        ];

        $this->getJson($this->endpoint. '?' . http_build_query($params))
            ->assertOk()
            ->assertJsonCount(2,'data');
    }
}
