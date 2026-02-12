<?php

namespace App\Http\Controllers\Dashboard;

use App\Actions\MealListJson;
use Inertia\Inertia;

class DefaultDashboard
{
     public function __invoke()
     {
          return Inertia::render('Dashboard',[
              'latestMeals' => MealListJson::handle(today()->subYears(4)->startOfDay(),today()->endOfDay())
          ]);
     }
}
