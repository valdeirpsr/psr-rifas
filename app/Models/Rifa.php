<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;

class Rifa extends Model
{
    /** @var int Número máximo de participantes na lista de ranking */
    private const MAX_RANKING = 3;

    /** @var string */
    public const STATUS_PUBLISHED = 'published';

    /** @var string */
    public const STATUS_FINISHED = 'finished';

    /** @var string */
    public const STATUS_DRAFT = 'draft';

    use HasFactory;

    protected $fillable = [
        'title',
        'thumbnail',
        'price',
        'description',
        'slug',
        'total_numbers_available',
        'buy_max',
        'buy_min',
        'raffle',
        'status',
        'expired_at',
        'ranking_buyer',
    ];

    public function price(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => floatval($value)
        );
    }

    public function slug(): Attribute
    {
        return Attribute::make(
            set: fn ($value, $attributes) => $attributes['slug'] ?? $value
        );
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function winners(): HasMany
    {
        return $this->hasMany(Winner::class);
    }

    public function ranking(): Collection
    {
        return $this->orders()
            ->select('customer_fullname', DB::raw('SUM(JSON_LENGTH(numbers_reserved)) as total_numbers'))
            ->where('status', Order::STATUS_PAID)
            ->groupBy('customer_telephone')
            ->orderBy('total_numbers', 'desc')
            ->limit(self::MAX_RANKING)
            ->get();
    }

    public static function availables(): Builder
    {
        return static::where('status', Rifa::STATUS_PUBLISHED)
            ->where(function ($query) {
                $query->where('expired_at', '>', now()->format('Y-m-d H:i'))
                    ->orWhereNull('expired_at');
            });
    }
}
