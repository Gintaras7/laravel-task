<?php

declare(strict_types=1);

namespace App\Clients;

use App\Contracts\ProductsClientContract;
use App\DTOs\ProductDTO;
use App\DTOs\StockDTO;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Http;

class KinfirmClient implements ProductsClientContract
{
    private PendingRequest $client;

    private string $productsEndpoint;

    private string $stocksEndpoint;

    public function __construct(array $config)
    {
        $this->productsEndpoint = $config['endpoints']['products'];
        $this->stocksEndpoint = $config['endpoints']['stocks'];

        $this->client = Http::baseUrl($config['base_url'])
            ->timeout(10)
            ->retry(3, 200)
            ->withOptions(['verify' => App::isProduction()])
            ->acceptJson();
    }

    /**
     * @return Collection<ProductDTO>
     *
     * @throws RequestException
     */
    public function getProducts(): Collection
    {
        $response = $this->client
            ->get($this->productsEndpoint)
            ->throw()
            ->json();

        return collect($response)->map(fn (array $product) => ProductDTO::fromArray($product));
    }

    /**
     * @return Collection<int, StockDTO>
     *
     * @throws RequestException
     */
    public function getStocks(): Collection
    {
        $response = $this->client
            ->get($this->stocksEndpoint)
            ->throw()
            ->json();

        return collect($response)->map(fn (array $stock) => StockDTO::fromArray($stock));
    }
}
