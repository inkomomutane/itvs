<?php

namespace App\Enum;

enum Sex : string
{
    use HasToObjectValues;
    use HasToArrayValues;

   case MALE = 'male';
   case FEMALE = 'female';
   case OTHER = 'other';
}
