<?php

namespace App\Actions;

use App\Models\User;
use Illuminate\Support\Carbon;

class TotalUsers
{

    /**
     * @return array{
     *      value : int,
     *      change_percentage : float
     * }
     */
    public static function handle(): array
    {
        $totalEmployee = User::count();
        $previousTotalEmployee = User::whereDate('created_at', '<', Carbon::now()->subDay())->count();
        $changePercentage = $previousTotalEmployee > 0 ? (($totalEmployee - $previousTotalEmployee) / $previousTotalEmployee) * 100 : 0;

        return [
            'value' => $totalEmployee,
            'change_percentage' => round($changePercentage, 2),
        ];
    }
}
