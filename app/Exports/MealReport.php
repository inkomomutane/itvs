<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class MealReport implements FromCollection,WithHeadings
{
    /**
    * @return Collection
    */
    public function collection()
    {
        //
    }

    public function headings(): array
    {
        return [
            "Data",
            "Funcinário",
            "Refeição",
            "Opção",
            "Estado", // Consumido / Não Consumido / Não compareceu
            "Hora de consumo",
        ];
    }
}
