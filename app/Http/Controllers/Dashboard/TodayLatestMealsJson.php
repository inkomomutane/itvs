<?php

namespace App\Http\Controllers\Dashboard;

use App\Actions\MealListJson;

class TodayLatestMealsJson
{
    public function __invoke()
    {
        return response()->json(MealListJson::handle(today()->subYears(2)->startOfDay(),today()->endOfDay()));
    }
}
