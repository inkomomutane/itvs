<?php


use App\Http\Controllers\Meal\ReserveMealController;
use App\Http\Controllers\Meal\ShowAvailableMeal;
use App\Http\Controllers\Meal\ReservedMealController;
use App\Http\Controllers\Meal\WorkerMealConfirmation;

Route::get('/ui', static function () {
    return \Inertia\Inertia::render('FrontPage');
})->name('ui.components');

Route::get('/meals/available', ShowAvailableMeal::class)->name('meals.available');
Route::post('/reserve/meal', ReserveMealController::class)->name('meals.reserve');
Route::get('/reserved/meals',ReservedMealController::class)->name('meals.reserved');
Route::post('reserved/meal/confirmation',WorkerMealConfirmation::class)->name('meals.confirmation');
