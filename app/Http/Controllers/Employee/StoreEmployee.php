<?php

namespace App\Http\Controllers\Employee;

use App\Data\AlertDto;
use App\Data\UserDto;
use App\Models\Role;
use App\Models\User;
use Hash;
use Throwable;

class StoreEmployee
{
    /**
     * @throws Throwable
     */
    public function __invoke(UserDto $dto)
    {
        try {
            \DB::beginTransaction();
            $user = User::create([
                'sap_number' => $dto->sap_number,
                'name' => $dto->name,
                'password' => Hash::make($dto->password),
                'company' => $dto->company,
                'department' => $dto->department,
                'active' => $dto->active,
            ]);

            $user->roles()->sync([Role::whereName('employee')->first()->id]);

            \DB::commit();
            return back()->with('messages', AlertDto::success(__('Created')));
        }catch (\Exception) {
            \DB::rollBack();
            return back()->with('messages', AlertDto::error(__('Not created')));
        }
    }
}
