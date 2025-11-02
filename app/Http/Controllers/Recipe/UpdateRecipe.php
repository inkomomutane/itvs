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
    public function __invoke(Recipe $recipe,RecipeDto $dto)
    {
        try {
            \DB::beginTransaction();
            $recipe->update([
                'name' => $dto->name,
                'description' => $dto->description,
                'date' => $dto->date,
                'period' => $dto->period
            ]);
            \DB::commit();
            return back()->with('messages', AlertDto::success(__('Updated')));
        }catch (\Exception) {
            \DB::rollBack();
            return back()->with('messages', AlertDto::error(__('Not updated')));
        }
    }
}
