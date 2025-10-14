<?php

namespace Tests\Feature\Commands;

use App\Contracts\ProductsClientContract;
use App\DTOs\StockDTO;
use App\Models\Product;
use App\Models\Stock;
use Illuminate\Support\Collection;
use Mockery;
use Mockery\MockInterface;
use PHPUnit\Framework\Attributes\Test;
use Symfony\Component\Console\Command\Command;
use Tests\TestCase;

class ImportStocksCommandTest extends TestCase
{
    #[Test]
    public function it_imports_stocks(): void
    {
        config(['queue.default' => 'sync']);

        $products = Product::factory()->count(3)->create();

        $stockDto = Collection::make([
            new StockDTO(sku: $products[0]->sku, city: 'Vilnius', stock: 50),
            new StockDTO(sku: $products[0]->sku, city: 'Kaunas', stock: 30),
            new StockDTO(sku: $products[1]->sku, city: 'Kaunas', stock: 70),
            new StockDTO(sku: 'Not Existing', city: 'Vilnius', stock: 10), // Should be ignored
        ]);

        $this->mockStocksClientContract($stockDto);

        // Act: Run the import command
        $this->artisan('import:stocks')->assertExitCode(Command::SUCCESS);

        $this->assertDatabaseCount(Stock::class, 4);
        $this->assertCount(2, $products[0]->fresh()->stocks);
        $this->assertCount(1, $products[1]->fresh()->stocks);
        $this->assertCount(0, $products[2]->fresh()->stocks);
    }

    #[Test]
    public function it_can_be_run_multiple_times_without_creating_duplicates(): void
    {
        config(['queue.default' => 'sync']);

        $products = Product::factory()->count(3)->create();

        $stockDto = Collection::make([
            new StockDTO(sku: $products[0]->sku, city: 'Vilnius', stock: 50),
            new StockDTO(sku: $products[0]->sku, city: 'Kaunas', stock: 30),
            new StockDTO(sku: $products[1]->sku, city: 'Kaunas', stock: 70),
            new StockDTO(sku: 'Not Existing', city: 'Vilnius', stock: 10), // Should be ignored
        ]);

        $this->mockStocksClientContract($stockDto);

        for ($i = 0; $i < 3; $i++) {
            $this->artisan('import:stocks')->assertExitCode(Command::SUCCESS);
        }

        $this->assertDatabaseCount(Stock::class, 4);
        $this->assertCount(2, $products[0]->fresh()->stocks);
        $this->assertCount(1, $products[1]->fresh()->stocks);
        $this->assertCount(0, $products[2]->fresh()->stocks);
    }

    private function mockStocksClientContract(Collection $stocks): void
    {
        $this->instance(
            ProductsClientContract::class,
            Mockery::mock(ProductsClientContract::class, function (MockInterface $mock) use ($stocks) {
                $mock->shouldReceive('getStocks')->andReturn($stocks);
            })
        );
    }
}
