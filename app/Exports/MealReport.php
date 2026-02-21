<?php

namespace App\Exports;

use App\Models\Meal;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;

class MealReport implements FromQuery, WithHeadings
{

    public function query()
    {
        return Meal::query()
            ->with('worker')
            // format date to d m Y in portuguese like 12 Jan 2025
            ->selectRaw("DATE_FORMAT(meal_date, '%d %b %Y') as exp_meal_date")
            // get worker name and sap number
            ->selectRaw("users.name as worker_name")
            ->selectRaw("users.sap_number as sap_number")
            // breakfast,lunch,snack and dinner to portuguese
            ->selectRaw("case when meals.period = 'breakfast' then 'Pequeno-almoço' when meals.period  = 'lunch' then 'Almoço' when meals.period  = 'snack' then 'Lanche' else 'Jantar' end as exp_period")
            // get recipe name
            ->selectRaw("recipes.name as recipe_name")
            // get meal status
            ->selectRaw("(case when status = 'Reserved' then 'Reservado' when status = 'Eaten' then 'Consumido' else 'cancelado' end) as exp_status")
            ->join('users', 'meals.worker_id', '=', 'users.id')
            ->join('recipes', 'meals.recipe_id', '=', 'recipes.id');
    }


    public function headings(): array
    {
        return [
            "Data",
            "Funcinário",
            'Número de SAP',
            "Refeição",
            "Opção",
            "Estado",
        ];
    }
}
