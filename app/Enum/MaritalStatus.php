<?php

namespace App\Enum;

enum MaritalStatus : string
{
    use HasToObjectValues;
    use HasToArrayValues;

    case SINGLE = 'single';
    case MARRIED = 'married';
    case DIVORCED = 'divorced';
    case WIDOWED = 'widowed';
}
