<?php


use App\Http\Controllers\Dashboard\TodayEatenMealsCountJson;
use App\Http\Controllers\Dashboard\TodayEmployeeCountJson;
use App\Http\Controllers\Dashboard\TodayLatestMealsJson;
use App\Http\Controllers\Dashboard\TodayMealsCountJson;
use App\Http\Controllers\Dashboard\TodayUsersCountJson;

Route::prefix('json')->group(function(){
   //TodayEmployeeCountJson
    Route::get('/today-employee-count', TodayEmployeeCountJson::class)->name('today-employee-count');
    #TodayUsersCountJson
    Route::get('/today-users-count', TodayUsersCountJson::class)->name('today-users-count');
    # TodayMealsCountJson
    Route::get('/today-meals-count', TodayMealsCountJson::class)->name('today-meals-count');
    #TodayEatenMealsCountJson
    Route::get('/today-eaten-meals-count', TodayEatenMealsCountJson::class)->name('today-eaten-meals-count');

    #MealListJson
    Route::get('/meal-list', TodayLatestMealsJson::class)->name('meal-list');
});
