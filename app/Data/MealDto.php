<?php

namespace App\Data;

use App\Enum\MealPeriod;
use App\Enum\MealStatus;
use App\Models\Meal;
use Illuminate\Support\Carbon;
use Spatie\LaravelData\Data;
/** @typescript  */
class MealDto extends Data
{
    public function __construct(
        public ?string     $id,
        public string      $recipe_name,
        public string      $worker_name,
        public ?string     $sap_number,
        public ?Carbon     $meal_date,
        public ?Carbon     $reserved_at,
        public ?Carbon     $served_at,
        public ?MealPeriod $period,
        public MealStatus  $status,
        public ?bool       $confirmedByWorker = false,
        public ?bool       $confirmedByChef = false,
    ) {}


    public static function fromModel(Meal $meal): self
    {
        return new self(
            id: $meal->id,
            recipe_name: $meal->recipe->name,
            worker_name: $meal->worker->name ?? 'N/A',
            sap_number: $meal->worker->sap_number ?? 'N/A',
            meal_date: $meal->meal_date,
            reserved_at: $meal->reserved_at,
            served_at: $meal->served_at,
            period: $meal->period,
            status: $meal->status,
            confirmedByWorker: $meal->worker_confirmation !== null,
            confirmedByChef: $meal->chef_confirmation !== null
        );
    }
}
