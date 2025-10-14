<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\ProductFactory;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

/**
 * Class Product
 *
 * @property int $id
 * @property string $sku
 * @property string|null $description
 * @property string|null $size
 * @property string|null $photo
 * @property Collection<Tag> $tags
 * @property Collection<Stock> $stocks
 * @property Carbon|null $external_updated_at
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
#[UseFactory(ProductFactory::class)]
class Product extends Model
{
    use HasFactory;

    public const CACHE_PREFIX = 'product_';

    protected $fillable = [
        'sku',
        'description',
        'size',
        'photo',
        'external_updated_at',
    ];

    protected $casts = [
        'external_update_at' => 'date',

    ];

    /**
     * Get the tags for the product.
     */
    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }

    /**
     * Get stock entries for the product.
     */
    public function stocks(): HasMany
    {
        return $this->hasMany(Stock::class, 'sku', 'sku');
    }
}
