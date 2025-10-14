<?php

declare(strict_types=1);

namespace App\Http\Resources\Api\V1;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @var Product $resource
 */
class ProductResource extends JsonResource
{
    public function __construct(Product $resource)
    {
        parent::__construct($resource);
    }

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->resource->id,
            'sku' => $this->resource->sku,
            'description' => $this->resource->description,
            'size' => $this->resource->size,
            'photo' => $this->resource->photo,
            'tags' => TagResource::collection($this->resource->tags),
            'stocks' => StockResource::collection($this->resource->stocks),
            'external_updated_at' => $this->resource->external_updated_at,
            'updated_at' => $this->resource->updated_at->toAtomString(),
            'created_at' => $this->resource->created_at->toAtomString(),
        ];
    }
}
