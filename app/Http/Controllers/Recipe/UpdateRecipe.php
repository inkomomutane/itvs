<?php

namespace App\Http\Controllers\Recipe;

use App\Data\AlertDto;
use App\Data\RecipeDto;
use App\Models\Recipe;

class UpdateRecipe
{
    /**
     * @throws \Throwable
     */
    public function __invoke(Recipe $user,RecipeDto $dto)
    {
        try {
            \DB::beginTransaction();
            $user->update([
                'name' => $dto->name,
                'description' => $dto->description,
                'date' => $dto->date,
            ]);
            \DB::commit();
            return back()->with('messages', AlertDto::success(__('Updated')));
        }catch (\Exception) {
            \DB::rollBack();
            return back()->with('messages', AlertDto::error(__('Not updated')));
        }
    }
}
