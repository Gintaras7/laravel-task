<?php

declare(strict_types=1);

namespace App\DTOs;

class ProductDTO
{
    public function __construct(
        private(set) string $sku,
        private(set) string $description,
        private(set) string $size,
        private(set) string $photo,
        private(set) array $tags,
        private(set) string $updatedAt,
    ) {}

    public static function fromArray(array $data): self
    {
        $tags = [];

        foreach ($data['tags'] as $tag) {
            $tags[] = new TagDTO($tag['title']);
        }

        return new self(
            sku: $data['sku'],
            description: $data['description'],
            size: $data['size'],
            photo: $data['photo'],
            tags: $tags,
            updatedAt: $data['updated_at'],
        );
    }
}
