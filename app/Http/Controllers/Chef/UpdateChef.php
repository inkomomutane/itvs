<?php

namespace App\Http\Controllers\Chef;

use App\Data\AlertDto;
use App\Data\UserDto;
use App\Models\Role;
use App\Models\User;

class UpdateChef
{
    /**
     * @throws \Throwable
     */
    public function __invoke(User $user,UserDto $dto)
    {
        try {
            \DB::beginTransaction();
            $user->update([
                'email' => $dto->email,
                'name' => $dto->name,
            ]);
            $user->roles()->sync([Role::whereName('chef')->first()->id]);
            \DB::commit();
            return back()->with('messages', AlertDto::success(__('Updated')));
        }catch (\Exception) {
            \DB::rollBack();
            return back()->with('messages', AlertDto::error(__('Not updated')));
        }
    }
}
