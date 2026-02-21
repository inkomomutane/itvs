<?php

namespace App\Http\Controllers\Meal;

use App\Exports\MealReport;

class ExportMealReport
{
    public function __invoke()
    {
        return \Excel::download(new MealReport(), 'meal_report.xlsx');
    }
}
