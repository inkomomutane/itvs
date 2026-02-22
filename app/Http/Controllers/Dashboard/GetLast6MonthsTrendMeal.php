<?php

namespace App\Http\Controllers\Dashboard;

use Illuminate\Support\Carbon;

class GetLast6MonthsTrendMeal
{
    public function __invoke()
    {
        return response()->json($this->getLast6MonthsTrend());
    }

    private function getLast6MonthsTrend()
    {
        $from = Carbon::now()->subDays(180)->startOfDay();
        $to   = Carbon::now()->endOfDay();
        return \DB::table('meals')
            ->selectRaw("
        DATE(meal_date) as date,
        COUNT(*) as reserved,
        SUM(CASE WHEN status = 'eaten' THEN 1 ELSE 0 END) as eaten
    ")
            ->whereBetween('meal_date', [$from, $to])
            ->groupBy(\DB::raw('DATE(meal_date)'))
            ->orderBy('date')
            ->get();
    }
}
