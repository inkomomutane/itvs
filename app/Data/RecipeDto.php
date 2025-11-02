<?php

namespace App\Data;

use App\Enum\MealPeriod;
use Illuminate\Support\Carbon;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\DateTimeInterfaceCast;
use Spatie\LaravelData\Data;
/** @typescript  */
class RecipeDto extends Data
{
    public function __construct(
        public ?string     $id,
        public string  $name,
        #[WithCast(DateTimeInterfaceCast::class,format:'Y-m-d')]
        public Carbon  $date,
        public ?MealPeriod $period,
        public ?string $description = '',
        public ?string  $ingredients ='',
        public ?string  $instructions = '',
        public ?bool   $active = true,
    ) {}
}
