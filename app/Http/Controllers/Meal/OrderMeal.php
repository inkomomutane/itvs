<?php

namespace App\Http\Controllers\Meal;

use App\Data\AlertDto;
use App\Enum\MealPeriod;
use App\Enum\MealStatus;
use App\Models\Recipe;
use App\Models\Role;

class OrderMeal
{
    /**
     * @throws \Throwable
     */
    public function __invoke(Recipe $recipe)
    {
        $user = auth()->user();

      # $user->roles()->sync([Role::whereName('employee')->first()->id]);
        # Rules
        # 1 Not accept to order same order twice.
        if ($user->meals()->where('recipe_id', $recipe->id)->whereDate('meal_date', $recipe->date)->where('period', $recipe->period)->exists()) {
            return back()->with('messages', AlertDto::success(__('You can not order same meal twice')));
        }
        # 2 only works can order meal.
        if ($user->roles()->first()->name !== 'employee') {
            return back()->with('messages', AlertDto::success(__('Only employees can order meal')));
        }
        try {
            \DB::beginTransaction();
            $user->meals()->create([
                'recipe_id' => $recipe->id,
                'meal_date' => $recipe->date,
                'period' => $recipe->period,
                'reserved_at' => now(),
                'served_at',
                'status' => MealStatus::Reserved,
                'worker_confirmation',
                'chef_confirmation',
                'confirmation_chef_id',
            ]);
            \DB::commit();
            return back()->with('messages', AlertDto::success(__('Meal ordered successfully')));
        } catch (\Exception $exception) {

            #throw  $exception;

            \DB::rollBack();
            return back()->with('messages', AlertDto::error(__('Meal not ordered')));
        }
    }
}
