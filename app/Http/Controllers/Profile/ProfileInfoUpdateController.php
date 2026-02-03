<?php

namespace App\Http\Controllers\Profile;

use App\Data\AlertDto;
use App\Data\UserDto;

class ProfileInfoUpdateController
{
    public function __invoke(UserDto $dto)
    {
        $user = auth()->user();
        # remove sap_number from the dto if it is not changed  and password
        $fields  = $dto->toArray();
        unset($fields['sap_number']);
        unset($fields['password']);
        $user->update($fields);
        $user->save();
        return redirect()->route('profile.base-info')->with('messages', AlertDto::success(__('Profile updated successfully')));
    }
}
