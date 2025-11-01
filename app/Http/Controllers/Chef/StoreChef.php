<?php

namespace App\Http\Controllers\Chef;

use App\Data\AlertDto;
use App\Data\UserDto;
use App\Models\Role;
use App\Models\User;
use Hash;
use Throwable;

class StoreChef
{
    /**
     * @throws Throwable
     */
    public function __invoke(UserDto $dto)
    {
        try {
            \DB::beginTransaction();
            $user = User::create([
                'email' => $dto->email,
                'name' => $dto->name,
                'password' => Hash::make($dto->password),
            ]);

            $user->roles()->sync([Role::whereName('chef')->first()->id]);

            \DB::commit();
            return back()->with('messages', AlertDto::success(__('Created')));
        }catch (\Exception $e) {
            \DB::rollBack();
            return back()->with('messages', AlertDto::error(__('Not created')));
        }
    }
}
