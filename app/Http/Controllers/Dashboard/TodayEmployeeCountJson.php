<?php

namespace App\Http\Controllers\Dashboard;

use App\Actions\TotalEmployee;

class TodayEmployeeCountJson
{
    public function __invoke()
    {
        return response()->json(TotalEmployee::handle());
    }
}
