<?php

namespace App\Http\Controllers\Meal;

use App\Data\AlertDto;
use App\Enum\MealStatus;
use App\Models\Meal;
use Illuminate\Http\Request;

class WorkerMealConfirmation
{
    /**
     * @throws \Throwable
     */
    public function __invoke(Request $request)
    {
        $validated = $request->validate([
            'meal_id' => ['required','string', 'exists:meals,id'],
            'sap_number' => ['required', 'string', 'exists:users,sap_number'],
        ]);

        $user = \App\Models\User::whereSapNumber($validated['sap_number'])->first();
        if(!$user){
            return back()->with('messages', AlertDto::error(__('Refeição não confirmada')));
        }

        $meal = Meal::where('id', $validated['meal_id'])->first();

        if(!$meal){
            return back()->with('messages', AlertDto::error(__('Refeição não confirmada')));
        }



        if($user->roles()->first()->name !== 'employee') {
            return back()->with('messages', AlertDto::success(__('Somente funcionários podem confirmar refeições')));
        }


        if ($meal->worker_id !== null && $meal->worker_id !== $user->id) {
            return back()->with('messages',AlertDto::success( __('Essa refeição não pertence ao funcionário que está tentando confirmar')) );
        }

        if ($meal->status === MealStatus::Eaten) {
            return back()->with('messages', AlertDto::success(__('Somente refeições reservadas podem ser confirmadas')));
        }

        if ($meal->worker_id !== null && $meal->worker_confirmation !== null) {
            return back()->with('messages', AlertDto::success(__('Esta refeição já foi confirmada antes')));
        }

        try {
            \DB::beginTransaction();
            $meal->update([
                'worker_confirmation' => now(),
                'worker_id' => $user->id,
                'status' =>  MealStatus::Eaten,
            ]);
            \DB::commit();
            return back()->with('messages',  AlertDto::success(__('Refeição confirmada com sucesso')));
        } catch (\Exception) {
            \DB::rollBack();
            return back()->with('messages', AlertDto::error(__('Refeição não confirmada')));
        }
    }
}
