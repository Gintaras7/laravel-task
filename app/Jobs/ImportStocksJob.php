<?php

namespace App\Jobs;

use App\Services\Products\ImportStocksService;
use Illuminate\Bus\Batchable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class ImportStocksJob implements ShouldQueue
{
    use Batchable, Queueable;

    /**
     * @param  Collection<StockDTO>  $stocks
     */
    public function __construct(private Collection $stocks)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(ImportStocksService $stockService): void
    {
        foreach ($this->stocks as $stockDto) {
            try {
                Log::info("Starting import stock SKU: {$stockDto->sku}");

                $savedStock = $stockService->import($stockDto);

                if (! blank($savedStock)) {
                    Log::info("Successfully imported stock SKU: {$stockDto->sku}", [
                        'stock_id' => $savedStock->id,
                    ]);
                }
            } catch (\Throwable $e) {
                Log::error("Failed to import stock SKU: {$stockDto->sku}", [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                ]);
            }
        }
    }
}
