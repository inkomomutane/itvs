<?php

namespace App\Data;

use Spatie\LaravelData\Data;

class RoleDto extends Data
{
    public function __construct(
        ?string $id,
        string $name
    ) {}
}
