<?php

namespace Tests\Feature\Controllers;

use App\Models\Product;
use App\Models\Stock;
use App\Models\Tag;
use Illuminate\Support\Facades\Cache;
use Illuminate\Testing\Fluent\AssertableJson;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ProductsControllerTest extends TestCase
{
    #[Test]
    public function it_returns_empty_product_list_when_no_products_exist(): void
    {
        $this->assertDatabaseCount(Product::class, 0);

        $result = $this->getJson(route('products.getList', ['page' => 1]));

        $result
            ->assertSuccessful()
            ->assertJsonStructure([
                'data',
                'meta' => ['total', 'per_page', 'current_page'],
            ]);

        $this->assertCount(0, $result['data']);
    }

    #[Test]
    public function it_returns_paginated_product_list_correctly(): void
    {
        Product::factory()->count(15)->create();

        $result = $this->getJson(route('products.getList', ['page' => 2]));

        $result
            ->assertSuccessful()
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'sku',
                        'description',
                        'size',
                        'photo',
                        'tags',
                        'stocks',
                        'external_updated_at',
                        'updated_at',
                        'created_at',
                    ],
                ],
                'meta' => ['total', 'per_page', 'current_page'],
            ]);

        $this->assertCount(5, $result['data']);
    }

    #[Test]
    public function it_returns_single_product_by_id_successfully(): void
    {
        $product = Product::factory()->create();

        $response = $this->getJson(route('products.getProduct', ['id' => $product->id]));

        $response
            ->assertOk()
            ->assertJson(
                fn (AssertableJson $json) => $json
                    ->where('id', $product->id)
                    ->where('sku', $product->sku)
                    ->where('description', $product->description)
                    ->etc()
            );
    }

    #[Test]
    public function it_returns_product_from_cache_if_available(): void
    {
        $product = Product::factory()->create();

        Cache::shouldReceive('has')
            ->once()
            ->with("product_{$product->id}")
            ->andReturn(true);

        Cache::shouldReceive('get')
            ->once()
            ->with("product_{$product->id}")
            ->andReturn($product);

        $response = $this->getJson(route('products.getProduct', ['id' => $product->id]));

        $response
            ->assertOk()
            ->assertJson(
                fn (AssertableJson $json) => $json
                    ->where('id', $product->id)
                    ->where('sku', $product->sku)
                    ->where('description', $product->description)
                    ->etc()
            );
    }

    #[Test]
    public function it_caches_product_when_not_already_cached(): void
    {
        $product = Product::factory()
            ->has(Tag::factory()->count(2), 'tags')
            ->has(Stock::factory()->count(1), 'stocks')
            ->create();

        $product->fresh();

        $tags = $product->tags;
        $stocks = $product->stocks;

        Cache::spy();

        $response = $this->getJson(route('products.getProduct', ['id' => $product->id]));

        Cache::shouldHaveReceived('has')->with("product_{$product->id}");
        Cache::shouldHaveReceived('put');

        $response
            ->assertOk()
            ->assertJson(
                fn (AssertableJson $json) => $json
                    ->where('id', $product->id)
                    ->where('sku', $product->sku)
                    ->where('size', $product->size)
                    ->where('photo', $product->photo)
                    ->where('description', $product->description)
                    ->where('external_updated_at', $product->external_updated_at)
                    ->has('tags', 2)
                    ->where('tags.0.id', $tags[0]->id)
                    ->where('tags.0.title', $tags[0]->title)
                    ->where('tags.1.id', $tags[1]->id)
                    ->where('tags.1.title', $tags[1]->title)
                    ->where('stocks.0.id', $stocks[0]->id)
                    ->where('stocks.0.stock', $stocks[0]->stock)
                    ->where('stocks.0.city.id', $stocks[0]->city->id)
                    ->where('stocks.0.city.title', $stocks[0]->city->title)
                    ->etc()
            );
    }

    #[Test]
    public function it_returns_404_when_product_does_not_exist(): void
    {
        $response = $this->getJson(route('products.getProduct', ['id' => 123]));

        $response->assertNotFound();
    }
}
