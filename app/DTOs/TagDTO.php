<?php

declare(strict_types=1);

namespace App\DTOs;

class TagDTO
{
    public function __construct(
        private(set) string $title,
    ) {}

    public static function fromArray(string $data): self
    {
        return new self(
            title: $data,
        );
    }
}
