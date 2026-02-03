<?php

namespace App\Http\Controllers\Meal;

use App\Enum\MealStatus;
use App\Models\User;
use Illuminate\Http\Request;

class ReservedMealController
{
    public function __invoke(Request $request)
    {
        $validated = $request->validate([
            'sap_number' => ['required', 'string', 'exists:users,sap_number'],
        ]);
        $user = User::whereSapNumber($validated['sap_number'])->first();

        if (!$user) {
            return response()->json([]);
        }

        $meals = $user->meals()->where('status', MealStatus::Reserved)->with('recipe')->get();
        return response()->json($meals);
    }
}
