<?php

namespace App\Exports;

use App\Data\MealReportRequestData;
use App\Models\Meal;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;

class MealReport implements FromQuery, WithHeadings
{
    public function __construct(
        public MealReportRequestData $request
    ){}

    public function query(): Builder|EloquentBuilder
    {
        return Meal::with(['recipe', 'worker'])->when($this->request->search, function ($query) {
            $query->whereHas('recipe', function ($query) {
                $query->whereAny(['name', 'description'], 'like', '%' . $this->request->search . '%');
            })->orWhereHas('worker', function ($query) {
                $query->whereAny(['name', 'sap_number', 'company', 'department'], 'like', '%' . $this->request->search . '%');
            });
        })->when($this->request->from, function ($query) {
            $query->whereDate('meals.meal_date', '>=', $this->request->from);
        })->when($this->request->to, function ($query) {
            $query->whereDate('meals.meal_date', '<=', $this->request->to);
        })->when($this->request->period, function ($query) {
            $query->where('meals.period', $this->request->period);
        })->when($this->request->user, function ($query) {
            $query->where('worker_id', $this->request->user);
        })->when($this->request->status, function ($query) {
            $query->where('meals.status', $this->request->status);
        })
            ->selectRaw("DATE_FORMAT(meal_date, '%d %b %Y') as exp_meal_date")
            ->selectRaw("users.name as worker_name")
            ->selectRaw("users.sap_number as sap_number")
            ->selectRaw("case when meals.period = 'breakfast' then 'Pequeno-almoço' when meals.period  = 'lunch' then 'Almoço' when meals.period  = 'snack' then 'Lanche' else 'Jantar' end as exp_period")
            ->selectRaw("recipes.name as recipe_name")
            ->selectRaw("(case when meals.status = 'Reserved' then 'Reservado' when meals.status = 'Eaten' then 'Consumido' else 'cancelado' end) as exp_status")
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
