<?php

namespace App\Jobs;

use App\DTOs\ProductDTO;
use App\Services\Products\ImportProductsService;
use Illuminate\Bus\Batchable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ImportProductsJob implements ShouldQueue
{
    use Batchable, Queueable;

    /**
     * @param  Collection<ProductDTO>  $products
     */
    public function __construct(private Collection $products)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(ImportProductsService $productService): void
    {
        foreach ($this->products as $productDto) {
            try {
                Log::info("Starting import for product SKU: {$productDto->sku}");

                $savedProduct = DB::transaction(
                    callback: fn () => $productService->import($productDto),
                    attempts: 3,
                );

                Log::info("Successfully imported product SKU: {$productDto->sku}", [
                    'product_id' => $savedProduct->id,
                ]);
            } catch (\Throwable $e) {
                Log::error("Failed to import product SKU: {$productDto->sku}", [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                ]);

                throw $e;
            }
        }
    }
}
