<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Filters\CategoryFilter;
use App\Filters\PriceFilter;
use App\Http\Requests\FetchProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Pipeline\Pipeline;

class ProductController extends Controller
{
    public function index(FetchProductRequest $request)
    {
        $products = app(Pipeline::class)
            ->send(Product::query()->with('attributes', 'images'))
            ->through([
                CategoryFilter::class,
                PriceFilter::class,
            ])
            ->thenReturn()
            ->paginate(
                $request->input('perPage', 15)
            );

        return ProductResource::collection($products);
    }
}
