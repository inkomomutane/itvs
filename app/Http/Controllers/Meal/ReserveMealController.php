<?php

namespace App\Http\Controllers\Meal;

use App\Data\AlertDto;
use App\Enum\MealStatus;
use App\Models\Recipe;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class ReserveMealController
{
    /**
     * @throws \Throwable
     */
    public function __invoke(Request $request)
    {

        $validated = $request->validate([
            'meal_id' => ['required','string', 'exists:recipes,id'],
            'sap_number' => ['required', 'string', 'exists:users,sap_number'],
            'date' => ['required', 'date', 'date_format:Y-m-d','after_or_equal:today'],
            'period' => ['required', 'string'],
        ]);

        if(!$validated){
            return back()->with('messages', AlertDto::error(__('Refeição não reservada')));
        }

        $recipe = Recipe::where('id', $validated['meal_id'])
            ->whereDate('date', Carbon::create($validated['date']))
            ->where('period', $validated['period'])
            ->first();

        if(!$recipe){
            return back()->with('messages', AlertDto::error(__('Refeição não reservada')));
        }

        $user = User::whereSapNumber($validated['sap_number'])->first();

        if(!$user){
            return back()->with('messages', AlertDto::error(__('Refeição não reservada')));
        }


        if ($user->meals()->where('recipe_id', $recipe->id)->whereDate('meal_date', $recipe->date)->where('period', $recipe->period)->exists()) {
            return back()->with('messages', AlertDto::success(__('Não é possível reservar a mesma refeição duas vezes')));
        }
        # 2 only works can order meal.
        if ($user->roles()->first()->name !== 'employee') {
            return back()->with('messages', AlertDto::success(__('Somente funcionários podem reservar refeições')));
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
            return back()->with('messages', AlertDto::success(__('Refeição reservada com sucesso')));
        } catch (\Exception $exception) {

            if(app()->isLocal()){
                throw  $exception;
            }

            \DB::rollBack();
            return back()->with('messages', AlertDto::error(__('Refeição não reservada')));
        }
    }
}
