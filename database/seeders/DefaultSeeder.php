<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Attribute;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DefaultSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->cleanTables();

        $supplierIds = User::factory()
            ->asSupplier()
            ->count(5)
            ->afterCreating(function (User $user) {
                $user->supplier()->create([
                    'name' => $user->name,
                    'tin' => fake()->randomNumber(9, true),
                    'phone' => fake()->phoneNumber(),
                    'website' => fake()->domainName(),
                    'address' => fake()->address,
                ]);
            })
            ->create()
            ->pluck('id')
            ->values();

        $categoryIds = Category::factory()
            ->count(10)
            ->create()
            ->pluck('id')
            ->values();

        Product::factory()
            ->count(30)
            ->has(ProductImage::factory()->count(2), 'images')
            ->has(Attribute::factory()->count(random_int(1, 5)), 'attributes')
            ->afterCreating(function (Product $product) use ($categoryIds) {
                $product->categories()->attach(array_values(fake()->randomElements($categoryIds, 3)));
            })
            ->state(new Sequence(
                fn (Sequence $sequence) => ['user_id' => fake()->randomElement($supplierIds)]
            ))
            ->create();
    }

    private function cleanTables(): void
    {
        Schema::disableForeignKeyConstraints();

        DB::table('attributes')->truncate();
        DB::table('categories')->truncate();
        DB::table('products')->truncate();
        DB::table('product_images')->truncate();
        DB::table('suppliers')->truncate();
        DB::table('users')->truncate();

        Schema::enableForeignKeyConstraints();
    }
}
