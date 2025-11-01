<?php

namespace App\Data;

use App\Enum\MealPeriod;
use Illuminate\Support\Facades\Date;
use Spatie\LaravelData\Data;
/** @typescript  */
class MealDto extends Data
{
    public function __construct(
        public ?int    $id,
        public string  $recipe_id,
        public string   $worker_id,
        public ?Date    $meal_date,
        public ?Date    $reserved_at,
        public ?Date    $served_at,
        public ?MealPeriod  $period,
        public ?bool   $active = true,
    ) {}
}
