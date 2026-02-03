<?php

namespace App\Enum;

enum MealPeriod: string
{

    use HasToArrayValues;
    use HasToObjectValues;

    case Breakfast = 'breakfast';
    case Lunch = 'lunch';
    case Dinner = 'dinner';
    case Snack = 'snack';
}
