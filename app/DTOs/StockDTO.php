<?php

declare(strict_types=1);

namespace App\DTOs;

class StockDTO
{
    public function __construct(
        private(set) string $sku,
        private(set) int $stock,
        private(set) string $city
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            sku: $data['sku'],
            stock: $data['stock'],
            city: $data['city']
        );
    }
}
