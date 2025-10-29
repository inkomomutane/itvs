<?php

namespace App\Enum;

enum MealStatus :  string
{
    use HasToArrayValues;
    use HasToObjectValues;

    case Reserved = 'Reserved';
    case Eaten = 'Eaten';
    case Canceled = 'Canceled';
}
