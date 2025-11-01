<?php

namespace App\Http\Controllers\Recipe;

use App\Data\AlertDto;
use App\Models\Recipe;

class DeleteRecipe
{
    /**
     * @throws \Throwable
     */
    public function __invoke(Recipe $recipe)
    {
        try {
            \DB::beginTransaction();
            $recipe->delete();
            \DB::commit();
            return back()->with('messages', AlertDto::success(__('Deleted')));
        }catch (\Exception) {
            \DB::rollBack();
            return back()->with('messages', AlertDto::error(__('Not deleted')));
        }
    }
}
