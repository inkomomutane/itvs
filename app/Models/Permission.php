<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;

class Permission extends \Spatie\Permission\Models\Permission
{
    use HasUlids;

    public $incrementing = false;


}
