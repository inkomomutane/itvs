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
        #[Required,Unique('users','sap_number',null,new RouteParameterReference('employee','id',true))]
        public string             $sap_number,
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
            sap_number: $user->sap_number,
            password: '',
            role: $user->roles()?->first()?->name
        );
    }

}
