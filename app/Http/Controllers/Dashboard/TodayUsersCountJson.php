<?php

namespace App\Http\Controllers\Dashboard;

use App\Actions\TotalUsers;

class TodayUsersCountJson
{
    public function __invoke()
    {
        return response()->json(TotalUsers::handle());
    }
}
