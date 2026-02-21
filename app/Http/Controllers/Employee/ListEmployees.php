<?php

namespace App\Http\Controllers\Employee;

use App\Data\RoleDto;
use App\Data\UserDto;
use App\Models\Role;
use App\Models\User;
use Inertia\Inertia;

class ListEmployees
{
    public function __invoke()
    {
        return Inertia::render('Employee/Index', [
            'employees' =>static::handle(request()->search),
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
                    'department',
                    'company'
                ], 'like', '%'.$search.'%');
            })
                ->orderBy('created_at', 'desc')
                ->whereRelation('roles','name','=','employee')
                ->paginate(5)
                ->withQueryString()
        );
    }
}
