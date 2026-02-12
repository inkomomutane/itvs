<?php

namespace App\Actions;

use App\Data\MealDto;
use App\Enum\MealStatus;
use App\Models\Meal;
use Illuminate\Support\Carbon;

class MealListJson
{
    public  static function handle(Carbon $from,Carbon $to, ?MealStatus $status = null) {


        return  MealDto::collect(Meal::with(['recipe', 'worker'])
            ->whereDate('meal_date','>=',$from)
            ->whereDate('meal_date','<=',$to)
            ->when($status, function ($query) use ($status) {
                $query->where('status', $status);
            })
            ->paginate());
    }
}
