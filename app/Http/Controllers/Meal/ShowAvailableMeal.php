<?php

namespace App\Http\Controllers\Meal;

use App\Enum\MealPeriod;
use App\Models\Recipe;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;

class ShowAvailableMeal
{
    public function __invoke(Request $request)
    {

        if(!$request->date || !$request->period){
            return response()->json([]);
        }

        $validated =  Validator::validate([
            'date' => $request->date,
            'period' => $request->period,
        ],[
            'date' => ['required','date','after_or_equal:today','date_format:Y-m-d'],
            'period' => ['required', 'string','in:'.implode(',',MealPeriod::toFlatValues())],
        ]);


        if(!$validated){
            return response()->json([]);
        }

        $meals = Recipe::where('period', $validated['period'])
            ->whereDate('date', Carbon::parse($validated['date']))
            ->get();
        return response()->json($meals);
    }
}
