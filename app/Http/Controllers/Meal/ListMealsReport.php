<?php

namespace App\Http\Controllers\Meal;

use App\Data\MealDto;
use App\Data\MealReportRequestData;
use App\Enum\MealPeriod;
use App\Enum\MealStatus;
use App\Models\Meal;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Fluent;
use Inertia\Inertia;

class ListMealsReport
{
    public function __invoke(Request $request)
    {


        $total = $this->calculateTotals(
            Carbon::parse($request->query('from', now()->startOfDay())),
            Carbon::parse($request->query('to', now()->endOfDay()))
        );
        return Inertia::render('Meal/MealReports', [
            'meals' => $this->handle(MealReportRequestData::from([
                'search' => $request->query('search'),
                'from' => $request->query('from', now()->startOfDay()),
                'to' => $request->query('to', now()->endOfDay()),
                'period' => $request->query('period'),
                'user' => $request->query('worker_id'),
                'status' => $request->query('status'),
            ])),
            'meal_statuses' => MealStatus::toValues(),
            'periods' => MealPeriod::toValues(),
            'users' => User::select('id', 'name')->get(),
            'total' => $total->total,
            'reserved' => $total->reserved,
            'eaten' => $total->eaten,
            'reserved_not_eaten_at_date' => $total->reserved_not_eaten_at_date,
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


    private function calculateTotals(Carbon $from, Carbon $to): Fluent
    {

        $baseQuery = Meal::whereBetween('meal_date', [$from, $to]);
        $reservedQuery = (clone $baseQuery)->where('status', MealStatus::Reserved);
        $eatenQuery = (clone $baseQuery)->where('status', MealStatus::Eaten);

        # get count of reserved meals that are not eaten and their meal date is in the past not including today
        $reservedNotEatenAtReservedDateQuery = (clone $reservedQuery)->whereIn('meal_date', function ($query) {
            $query->select('meal_date')
                ->from('meals')
                ->where('status', MealStatus::Reserved)
                ->whereDate('meal_date', '<', now()->startOfDay());
        })
            ->whereNotIn('id', function ($query) {
                $query->select('id')
                    ->from('meals')
                    ->where('status', MealStatus::Eaten);
            });

        return Fluent::make([
            'total' => $baseQuery->count(),
            'reserved' => $reservedQuery->count(),
            'eaten' => $eatenQuery->count(),
            'reserved_not_eaten_at_date' => $reservedNotEatenAtReservedDateQuery->count(),
        ]);
    }
}
