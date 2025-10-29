<?php

namespace App\Http\Controllers\Employee;

use App\Data\UserDto;
use App\Models\User;
use Inertia\Inertia;

class ListEmployees
{
    public function __invoke()
    {
        return Inertia::render('Employee/Index', [
            'employees' =>static::handle(request()->search),
        ]);
    }

    public static function handle(?string $term = ''): mixed
    {
        return UserDto::collect(
            User::query()->when($term, function ($query, $search) {
                $query->whereAny([
                    'name',
                    'email',
                    'password',
                ], 'like', '%'.$search.'%');
            })
                ->orderBy('created_at', 'desc')
                ->paginate(5)
                ->withQueryString()
        );
    }
}
