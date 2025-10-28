<?php

namespace App\Data;

use App\Models\User;
use Spatie\LaravelData\Attributes\Validation\Email;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Data;

/** @typescript  */
class UserDto extends Data
{
    public function __construct(
        public string             $name,
        #[Required,Email]
        public string             $email,
        public ?string            $password = '',
    )
    {
    }


    public static function fromModel(User $user): self
    {
        return new self(
            name: $user->name,
            email: $user->email,
            password: '',
        );
    }

}
