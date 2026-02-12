<?php

namespace App\Actions;

use App\Models\User;
use Illuminate\Support\Carbon;

class TotalEmployee
{

    /**
     * @return array{
     *      value : int,
     *      change_percentage : float
     * }
     */
    public static function handle(): array
    {
        $totalEmployee = User::whereHas('roles', static function ($query) {
            $query->where('name', 'employee');
        })->count();
        $previousTotalEmployee = User::whereHas('roles', static function ($query) {
            $query->where('name', 'employee');
        })->whereDate('created_at', '<', Carbon::now()->subDay())->count();
        $changePercentage = $previousTotalEmployee > 0 ? (($totalEmployee - $previousTotalEmployee) / $previousTotalEmployee) * 100 : 0;

        return [
            'value' => $totalEmployee,
            'change_percentage' => round($changePercentage, 2),
        ];
    }
}
