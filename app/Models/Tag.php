<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\TagFactory;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

/**
 * Class Tag
 *
 * @property int $id
 * @property string $title
 * @property Collection<Product> $products
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
#[UseFactory(TagFactory::class)]
class Tag extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
    ];

    /**
     * Get the products associated with the tag.
     */
    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class);
    }
}
