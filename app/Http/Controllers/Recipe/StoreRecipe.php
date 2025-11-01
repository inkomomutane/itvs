<?php

namespace App\Http\Controllers\Recipe;

use App\Data\AlertDto;
use App\Data\RecipeDto;
use App\Models\Recipe;
use Throwable;

class StoreRecipe
{
    /**r
     * @throws Throwable
     */
    public function __invoke(RecipeDto $dto)
    {
        try {
            \DB::beginTransaction();
             Recipe::create([
                 'name' => $dto->name,
                 'description' => $dto->description,
                 'date' => $dto->date,
            ]);

            \DB::commit();
            return back()->with('messages', AlertDto::success(__('Created')));
        }catch (\Exception $e) {
            \DB::rollBack();
            return back()->with('messages', AlertDto::error(__('Not created')));
        }
    }
}
