<?php

namespace App\Http\Controllers\Employee;

use App\Data\AlertDto;
use App\Data\UserDto;
use App\Models\Role;
use App\Models\User;
use Throwable;

class UpdateEmployee
{
    /**
     * @throws Throwable
     */
    public function __invoke(User $employee,UserDto $dto)
    {
        try {
            \DB::beginTransaction();
            $employee->update([
                'sap_number' => $dto->sap_number,
                'name' => $dto->name,
                'company' => $dto->company,
                'department' => $dto->department,
                'active' => $dto->active,
            ]);
            $employee->roles()->sync([Role::whereName('employee')->first()->id]);
            \DB::commit();
            return back()->with('messages', AlertDto::success(__('Updated')));
        }catch (\Exception) {
            \DB::rollBack();
            return back()->with('messages', AlertDto::error(__('Not updated')));
        }
    }
}
