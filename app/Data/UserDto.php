<?php

namespace App\Data;

use App\Models\User;
use Spatie\LaravelData\Attributes\Validation\Email;
use Spatie\LaravelData\Attributes\Validation\Password;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Attributes\Validation\Unique;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Support\Validation\References\RouteParameterReference;

/** @typescript  */
class UserDto extends Data
{
    public function __construct(
        public ?string $id,
        public string             $name,
        #[Required,Email,Unique('users','email',null,new RouteParameterReference('user','id',true))]
        public string             $email,
        #[Password]
        public ?string            $password = '',
        public ?string            $role,
    )
    {
    }


    public static function fromModel(User $user): self
    {
        return new self(
            id: $user->id,
            name: $user->name,
            email: $user->email,
            password: '',
            role: $user->roles()?->first()?->name
        );
    }

}
