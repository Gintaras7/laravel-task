<?php

declare(strict_types=1);

namespace App\Services\Products;

use App\Models\Tag;
use Illuminate\Support\Collection;

class TagService
{
    /**
     * @return Collection<Tag>
     */
    public function getMostPopularList(int $limit = 10): Collection
    {
        $popularTags = Tag::query()
            ->withCount('products')
            ->orderByDesc('products_count')
            ->limit($limit)
            ->get();

        return $popularTags;
    }
}
