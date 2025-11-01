<?php

namespace App\Http\Controllers\Chef;

use App\Data\AlertDto;
use App\Models\User;

class DeleteChef
{
    /**
     * @throws \Throwable
     */
    public function __invoke(User $user)
    {
        try {
            \DB::beginTransaction();
            $user->roles()->sync([]);
            $user->delete();
            \DB::commit();
            return back()->with('messages', AlertDto::success(__('Deleted')));
        }catch (\Exception) {
            \DB::rollBack();
            return back()->with('messages', AlertDto::error(__('Not deleted')));
        }
    }
}
