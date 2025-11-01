<?php

namespace App\Http\Controllers\Recipe;

use App\Data\RecipeDto;
use App\Models\Recipe;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Inertia\Inertia;

class ListRecipes
{
    public function __invoke(Request $request)
    {
        return Inertia::render('Recipe/Index', [
            'recipes' => static::handle(Carbon::create($request->get('meal_date',now()->format('Y-m-d'))),request()->search),
            'date' => $request->get('meal_date',now()->format('Y-m-d'))
        ]);
    }

    public static function handle(Carbon $date,?string $term = ''): mixed
    {
        return RecipeDto::collect(
            Recipe::query()->when($term, function ($query, $search) {
                $query->whereAny([
                    'name',
                    'description',
                ], 'like', '%' . $search . '%');
            })
                ->orderBy('created_at', 'desc')
                ->paginate(5)
                ->withQueryString()
        );
    }
}
