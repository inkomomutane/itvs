<?php

namespace App\Data;

use Spatie\LaravelData\Data;

class RecipeDto extends Data
{
    public function __construct(
        public ?int    $id,
        public string  $name,
        public ?string $description,
        public ?string $ingredients,
        public ?string $instructions,
        public ?bool   $active = true,
    ) {}
}
