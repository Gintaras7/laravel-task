<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Tag;
use Illuminate\Support\Collection;

class TagRepository
{
    /**
     * @return Collection<Tag>
     */
    public function getPopularByProductCount(int $limit = 10): Collection
    {
        $popularTags = Tag::query()
            ->withCount('products')
            ->orderByDesc('products_count')
            ->limit($limit)
            ->get();

        return $popularTags;
    }
}
