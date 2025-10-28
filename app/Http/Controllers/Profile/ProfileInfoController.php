<?php

namespace App\Http\Controllers\Profile;

use App\Data\UserDto;
use Inertia\Inertia;

class ProfileInfoController
{
    public function __invoke()
    {
        return Inertia::render('Profile/Index', [
            'user' => UserDto::from( auth()->user()->toArray()),
        ]);
    }
}
