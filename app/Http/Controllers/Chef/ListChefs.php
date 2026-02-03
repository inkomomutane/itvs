<?php

namespace App\Http\Controllers\Chef;

use App\Data\RoleDto;
use App\Data\UserDto;
use App\Models\Role;
use App\Models\User;
use Inertia\Inertia;

class ListChefs
{
    public function __invoke()
    {
        return Inertia::render('Chef/Index', [
            'chefs' =>static::handle(request()->search),
            'roles' => RoleDto::collect(Role::all())
        ]);
    }

    public static function handle(?string $term = ''): mixed
    {
        return UserDto::collect(
            User::query()->when($term, function ($query, $search) {
                $query->whereAny([
                    'name',
                    'sap_number',
                ], 'like', '%'.$search.'%');
            })
                ->orderBy('created_at', 'desc')
                ->whereRelation('roles','name','=','chef')
                ->paginate(5)
                ->withQueryString()
        );
    }
}
