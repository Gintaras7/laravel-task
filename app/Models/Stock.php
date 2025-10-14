<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\StockFactory;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * Class Stock
 *
 * @property int $id
 * @property string $sku
 * @property int|null $stock
 * @property City|null $city
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
#[UseFactory(StockFactory::class)]
class Stock extends Model
{
    use HasFactory;

    protected $fillable = [
        'city_id',
        'stock',
    ];

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }
}
