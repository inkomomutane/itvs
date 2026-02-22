<?php

namespace App\Data;

use App\Enum\MealPeriod;
use App\Enum\MealStatus;
use Illuminate\Support\Carbon;
use Spatie\LaravelData\Data;

class MealReportRequestData extends Data
{
    public function __construct(
         public ?string $search = null,
         public ?Carbon $from = null,
         public ?Carbon $to = null,
         public ?MealPeriod  $period = null,
         public ?string  $user = null,
         public ?MealStatus  $status = null,
    ) {}
}
