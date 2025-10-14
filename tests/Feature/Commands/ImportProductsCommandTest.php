<?php

namespace Tests\Feature\Commands;

use App\Contracts\ProductsClientContract;
use App\DTOs\ProductDTO;
use App\DTOs\TagDTO;
use App\Models\Product;
use App\Models\Tag;
use Illuminate\Support\Collection;
use Mockery;
use Mockery\MockInterface;
use PHPUnit\Framework\Attributes\Test;
use Symfony\Component\Console\Command\Command;
use Tests\TestCase;

class ImportProductsCommandTest extends TestCase
{
    #[Test]
    public function it_imports_products_from_external_client(): void
    {
        // Arrange
        $this->assertDatabaseCount(Product::class, 0);

        $productDtos = $this->makeProductCollection(10);
        $this->mockProductsClientContract($productDtos);

        // Act
        $this->artisan('import:products')->assertExitCode(Command::SUCCESS);

        // Assert
        foreach ($productDtos as $dto) {
            $this->assertDatabaseHas(Product::class, [
                'sku' => $dto->sku,
                'description' => $dto->description,
            ]);
        }

        $this->assertDatabaseCount(Product::class, 10);
    }

    #[Test]
    public function it_imports_products_with_attached_tags(): void
    {
        // Arrange
        $this->assertDatabaseCount(Product::class, 0);
        $this->assertDatabaseCount(Tag::class, 0);

        $productDtos = $this->makeProductCollection(5, ['Eco', 'New']);
        $this->mockProductsClientContract($productDtos);

        // Act
        $this->artisan('import:products')->assertExitCode(Command::SUCCESS);

        // Assert
        $this->assertDatabaseCount(Product::class, 5);
        $this->assertDatabaseCount(Tag::class, 2); // Only two unique tags

        foreach ($productDtos as $dto) {
            $product = Product::where('sku', $dto->sku)->with('tags')->first();

            $this->assertNotNull($product, "Product with SKU {$dto->sku} was not found in the database.");
            $this->assertCount(count($dto->tags), $product->tags, "Product {$dto->sku} should have correct number of tags.");
        }
    }

    /**
     * @return Collection<ProductDTO>
     */
    private function makeProductCollection(int $count, array $tags = []): Collection
    {
        return collect(range(1, $count))->map(fn ($i) => new ProductDTO(
            sku: "KF-00$i",
            description: "Product $i description",
            size: '2XL',
            photo: 'http://dummyimage.com/125x100.png/cc0000/ffffff',
            updatedAt: now()->toDateString(),
            tags: array_map(fn ($tag) => new TagDTO($tag), $tags)
        ));
    }

    private function mockProductsClientContract(Collection $products): void
    {
        $this->instance(
            ProductsClientContract::class,
            Mockery::mock(ProductsClientContract::class, function (MockInterface $mock) use ($products) {
                $mock->shouldReceive('getProducts')->once()->andReturn($products);
            })
        );
    }
}
