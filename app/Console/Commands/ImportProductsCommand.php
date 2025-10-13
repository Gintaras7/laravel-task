<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Contracts\ProductsClientContract;
use App\Jobs\ImportProductsJob;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Log;

class ImportProductsCommand extends Command
{
    const BATCH_SIZE = 20;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:products';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle(ProductsClientContract $productsClient)
    {

        try {
            $products = $productsClient->getProducts();
            $total = $products->count();
            $batches = $products->chunk(self::BATCH_SIZE);

            $this->info("Total products {$total}");
            $this->info('Batch size '.self::BATCH_SIZE);

            $jobs = $batches->map(fn ($batch) => new ImportProductsJob($batch))->all();

            Bus::batch($jobs)->dispatch();

            $this->info(count($batches).' batches dispatched');

            return Command::SUCCESS;
        } catch (\Throwable $throwable) {
            $this->error('error while running command: '.$throwable->getMessage());
            Log::debug('Failed to import products', [
                'message' => $throwable->getMessage(),
                'exception' => $throwable,
            ]);

            return Command::FAILURE;
        }
    }
}
