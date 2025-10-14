<?php

declare(strict_types=1);

namespace App\Http\Resources\Api\V1;

use App\Models\Stock;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StockResource extends JsonResource
{
    public function __construct(Stock $resource)
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
            'stock' => $this->resource->stock,
            'city' => new CityResource($this->resource->city ?? new City),
            'updated_at' => $this->resource->updated_at->toAtomString(),
            'created_at' => $this->resource->created_at->toAtomString(),
        ];
    }
}
