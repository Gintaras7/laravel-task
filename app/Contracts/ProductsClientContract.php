<?php

declare(strict_types=1);

namespace App\Contracts;

use App\DTOs\ProductDTO;
use App\DTOs\StockDTO;
use Illuminate\Support\Collection;

interface ProductsClientContract
{
    /**
     * @return Collection<ProductDTO>
     */
    public function getProducts(): Collection;

    /**
     * @return Collection<StockDTO>
     */
    public function getStocks(): Collection;
}
