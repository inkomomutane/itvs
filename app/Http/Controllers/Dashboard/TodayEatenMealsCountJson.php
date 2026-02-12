<?php

namespace App\Http\Controllers\Dashboard;

use App\Actions\TotalEatenMeals;
use App\Actions\TotalMeals;

class TodayEatenMealsCountJson
{
    public function __invoke()
    {
        return response()->json(TotalEatenMeals::handle(today()->startOfDay(),today()->endOfDay()));
    }
}
