<?php

namespace App\Http\Controllers\Meal;

use App\Data\MealReportRequestData;
use App\Exports\MealReport;
use PhpOffice\PhpSpreadsheet\Exception;

class ExportMealReport
{
    /**
     * @throws Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    public function __invoke()
    {
        return \Excel::download(new MealReport(MealReportRequestData::from([
            'search' => request()->query('search'),
            'from' => request()->query('from'),
            'to' => request()->query('to'),
            'period' => request()->query('period'),
            'user' => request()->query('worker_id'),
            'status' => request()->query('status'),
        ])), 'meal_report.xlsx');
    }
}
