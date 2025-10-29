<?php

namespace App\Enum;

enum MealPeriod: string
{

    use HasToArrayValues;
    use HasToObjectValues;

    case Breakfast = 'Breakfast';
    case Lunch = 'Lunch';
    case Dinner = 'Dinner';
}
