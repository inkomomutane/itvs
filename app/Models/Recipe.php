<?php

namespace App\Models;

use App\Enum\MealPeriod;
use Database\Factories\RecipeFactory;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Recipe extends Model implements HasMedia
{
    /** @use HasFactory<RecipeFactory> */
    use HasFactory;
    use HasUlids;
    use InteractsWithMedia;

    public $incrementing = false;

    protected $fillable = [
        'name',
        'description',
        'ingredients',
        'instructions',
        'period',
        'active',
        'date',
    ];

    protected function casts(): array
    {
        return [
            'date' => 'date:Y-m-d',
            'active' => 'boolean',
            'period' => MealPeriod::class
        ];
    }


    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('avatar')->singleFile();
    }
}
