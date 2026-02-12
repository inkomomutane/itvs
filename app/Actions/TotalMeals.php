<?php

namespace App\Actions;

use App\Models\Meal;
use Illuminate\Support\Carbon;

class TotalMeals
{

    /**
     * @return array{
     *      value : int,
     *      change_percentage : float
     * }
     */
    public static function handle(Carbon $from, Carbon $to): array
    {
        $totalMeals =  Meal::whereBetween('meal_date', [$from, $to])->count();
        $previousTotalMeals = Meal::whereBetween('meal_date', [$from->copy()->subDay(), $to->copy()->subDay()])->count();
        $changePercentage = $previousTotalMeals > 0 ? (($totalMeals - $previousTotalMeals) / $previousTotalMeals) * 100 : 0;

        return [
            'value' => $totalMeals,
            'change_percentage' => round($changePercentage, 2),
        ];
    }
}
