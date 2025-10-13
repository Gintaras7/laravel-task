<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * Class Stock
 *
 * @property int $id
 * @property int $product_id
 * @property string|null $city
 * @property int|null $stock
 * @property Product $product
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class Stock extends Model
{
    protected $fillable = [
        'product_id',
        'city',
        'stock',
    ];

    /**
     * Get the products associated with the tag.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
