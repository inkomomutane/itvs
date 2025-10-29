<?php

namespace App\Models;

use App\Enum\MealPeriod;
use App\Enum\MealStatus;
use Database\Factories\MealFactory;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Meal extends Model
{
    /** @use HasFactory<MealFactory> */
    use HasFactory;
    use HasUlids;

    public $incrementing = false;

    protected $fillable = [
        'recipe_id',
        'worker_id',
        'meal_date',
        'period',
        'reserved_at',
        'served_at',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'meal_date' => 'date:Y-m-d',
            'period' => MealPeriod::class,
            'status' => MealStatus::class
        ];
    }


    public function recipe(): BelongsTo
    {
        return $this->belongsTo(Recipe::class);
    }

    public function worker(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
