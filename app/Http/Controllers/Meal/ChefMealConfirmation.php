<?php

namespace App\Http\Controllers\Meal;

use App\Enum\MealStatus;
use App\Models\Meal;

class ChefMealConfirmation
{
    /**
     * @throws \Throwable
     */
    public function __invoke(Meal $meal)
    {
        $user = auth()->user();

        if($user->roles()->first()->name !== 'chef') {
            return back()->with('messages', __('Only chefs are allowed to confirm this meal here.'));
        }


        if ($meal->chef_confirmation !== null && $meal->confirmation_chef_id !== $user->id) {
            return back()->with('messages', __('You are not authorized to confirm this meal'));
        }

        if ($meal->status !== MealStatus::Eaten) {
            return back()->with('messages', __('Only served meals can be confirmed'));
        }

        if ($meal->confirmation_chef_id !== null || $meal->chef_confirmation !== null) {
            return back()->with('messages', __('This meal has already been confirmed'));
        }

        try {
            \DB::beginTransaction();
            $meal->update([
                'chef_confirmation' => now(),
                'confirmation_chef_id' => $user->id,
            ]);
            \DB::commit();
            return back()->with('messages', __('Meal confirmed'));
        } catch (\Exception) {
            \DB::rollBack();
            return back()->with('messages', __('Meal not confirmed'));
        }
    }
}
