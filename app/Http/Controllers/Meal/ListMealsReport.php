<?php

namespace App\Http\Controllers\Meal;

use App\Data\MealDto;
use App\Data\MealReportRequestData;
use App\Enum\MealPeriod;
use App\Enum\MealStatus;
use App\Models\Meal;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ListMealsReport
{
    public function __invoke(Request $request)
    {
        return Inertia::render('Meal/MealReports', [
            'meals' => $this->handle(MealReportRequestData::from([
                'search' => $request->query('search'),
                'from' => $request->query('from'),
                'to' => $request->query('to'),
                'periods' => [],
                'users' => [],
                'statuses' => []
            ])),
            'meal_statuses' => MealStatus::toValues(),
            'periods' => MealPeriod::toValues(),
        ]);
    }

    private function handle(MealReportRequestData $request)
    {
        return MealDto::collect(Meal::with(['recipe', 'worker'])->when($request->search, function ($query) use ($request) {
            $query->whereHas('recipe', function ($query) use ($request) {
                $query->whereAny(['name', 'description'], 'like', '%' . $request->search . '%');
            })->orWhereHas('worker', function ($query) use ($request) {
                $query->whereAny(['name', 'sap_number', 'company', 'department'], 'like', '%' . $request->search . '%');
            });
        })->when($request->from, function ($query) use ($request) {
            $query->whereDate('meal_date', '>=', $request->from);
        })->when($request->to, function ($query) use ($request) {
            $query->whereDate('meal_date', '<=', $request->to);
        })->when($request->period, function ($query) use ($request) {
            $query->where('period', $request->period);
        })->when($request->user, function ($query) use ($request) {
            $query->where('worker_id', $request->user);
        })->when($request->status, function ($query) use ($request) {
            $query->where('status', $request->status);
        })->paginate(12)->withQueryString());
    }
}
