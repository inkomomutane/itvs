<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;

class Users implements FromQuery,WithHeadings
{

    public function query()
    {
        return \App\Models\User::query()
            ->with('roles')
            ->select([
                'users.name',
                'users.sap_number',
                'users.company',
                'users.department',
            ])
            ->selectRaw('(case when users.active then "Activo" else "Inactivo" end) as status');
    }

    public function headings(): array
    {
        return [
            'Nome',
            'Número de Sap',
            'Empresa',
            'Departamento',
            'Estado',
        ];
    }
}
