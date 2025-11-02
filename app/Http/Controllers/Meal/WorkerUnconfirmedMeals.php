<?php

namespace App\Http\Controllers\Meal;

use App\Data\MealDto;
use App\Data\RecipeDto;
use App\Models\Recipe;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Inertia\Inertia;

class WorkerUnconfirmedMeals
{
    public function __invoke(Request $request)
    {
        return Inertia::render('Meal/WorkerOrders', [
            'orders' => static::handle(request()->search, Carbon::create($request->get('date', now()->format('Y-m-d')))),
            'recipes' => RecipeDto::collect(
                Recipe::query()->whereDate('date', $request->get('date', now()->format('Y-m-d')))
                    ->orderBy('date', 'desc')->get()
            ),
            'date' => $request->get('date', now()->format('Y-m-d')),
        ]);
    }


    public static function handle(?string $term = '', ?Carbon $date = null): mixed
    {
        $user = auth()->user();
        return MealDto::collect(
            $user->meals()
                ->when($term, function ($query, $search) {
                    $query->whereHas('recipe', function ($q) use ($search) {
                        $q->whereAny([
                            'name',
                            'description',
                        ], 'like', '%' . $search . '%');
                    });
                })
                ->when($date, function ($query, $date) {
                    $query->whereDate('meal_date', $date);
                })
                ->orderBy('meal_date', 'desc')
                ->whereNull('worker_confirmation')
                ->with('recipe')
                ->get()
        );
    }
}
