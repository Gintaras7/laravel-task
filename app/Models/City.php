<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

/**
 * Class City
 *
 * @property int $id
 * @property string $title
 * @property Collection|Stock[] $stocks
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class City extends Model
{
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
