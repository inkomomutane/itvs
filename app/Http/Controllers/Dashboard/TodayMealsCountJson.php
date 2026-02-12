<?php

namespace App\Http\Controllers\Dashboard;

use App\Actions\TotalMeals;

class TodayMealsCountJson
{
    public function __invoke()
    {
        return response()->json(TotalMeals::handle(today()->startOfDay(),today()->endOfDay()));
    }
}
