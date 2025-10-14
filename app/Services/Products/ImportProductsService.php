<?php

declare(strict_types=1);

namespace App\Services\Products;

use App\DTOs\ProductDTO;
use App\DTOs\TagDTO;
use App\Models\Product;
use App\Models\Tag;
use Illuminate\Support\Collection;

class ImportProductsService
{
    public function import(ProductDTO $productDto): Product
    {
        $product = Product::query()->where('sku', $productDto->sku)->first();
        if (blank($product)) {
            $product = new Product;
        }

        $product->sku = $productDto->sku;
        $product->description = $productDto->description;
        $product->size = $productDto->size;
        $product->photo = $productDto->photo;
        $product->external_updated_at = $productDto->updatedAt;
        $product->save();

        $tags = $this->importTags($productDto->tags);
        $product->tags()->sync($tags);

        return $product;
    }

    /**
     * @param  array<TagDTO>  $tags
     * @return \Illuminate\Support\Collection<Tag>
     */
    public function importTags(array $tags): Collection
    {
        if (blank($tags)) {
            return Collection::make();
        }

        $tagTitles = collect($tags)->map(fn ($tag) => $tag->title)->unique();

        $existingTitles = Tag::query()
            ->whereIn('title', $tagTitles)
            ->pluck('title');

        $newTitles = $tagTitles->diff($existingTitles);

        if ($newTitles->isNotEmpty()) {
            $insertData = $newTitles->map(fn ($title) => [
                'title' => $title,
            ])->toArray();

            Tag::insert($insertData);
        }

        return Tag::whereIn('title', $tagTitles)->get();
    }
}
