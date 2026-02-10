<?php


use App\Http\Controllers\Chef\DeleteChef;
use App\Http\Controllers\Chef\ListChefs;
use App\Http\Controllers\Chef\StoreChef;
use App\Http\Controllers\Chef\UpdateChef;
use App\Http\Controllers\Employee\DeleteEmployee;
use App\Http\Controllers\Employee\ListEmployees;
use App\Http\Controllers\Employee\StoreEmployee;
use App\Http\Controllers\Employee\UpdateEmployee;
use App\Http\Controllers\Meal\ChefMealConfirmation;
use App\Http\Controllers\Meal\OrderMeal;
use App\Http\Controllers\Meal\WorkerMealConfirmation;
use App\Http\Controllers\Meal\WorkerUnconfirmedMeals;
use App\Http\Controllers\Profile\ProfileInfoController;
use App\Http\Controllers\Profile\ProfileInfoUpdateController;
use App\Http\Controllers\Profile\UploadMemberPicture;
use App\Http\Controllers\Recipe\DeleteRecipe;
use App\Http\Controllers\Recipe\ListRecipes;
use App\Http\Controllers\Recipe\StoreRecipe;
use App\Http\Controllers\Recipe\UpdateRecipe;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', static function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('dashboard',static  function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


Route::get('/profile/base-info', ProfileInfoController::class)->middleware(['auth', 'verified'])->name('profile.base-info');
Route::post('/profile/base-info-update', ProfileInfoUpdateController::class)->middleware(['auth', 'verified'])->name('profile.base-info-update');
Route::get('/profile/view/member/card', static function(){
    return Inertia::render('MemberCard',[
        'member' => auth()->user()->getData(),
    ]);
})->name( 'member.card')->middleware(['auth','verified']);

Route::post('member-upload-picture/{member}',UploadMemberPicture::class)->name('member-upload-picture')->middleware(['auth','verified']);

# Employee

Route::middleware(['auth','verified'])->prefix('dashboard')->group(function (){
    Route::get('/list-employees',ListEmployees::class)->name('list-employees');
    Route::post('/store-employee', StoreEmployee::class)->name('store-employee');
    Route::match(['post','put','path'],'/update-employee/employee/{employee}', UpdateEmployee::class)->name('update-employee');
    Route::delete('/delete-employee/{user}', DeleteEmployee::class)->name('delete-employee');

    Route::get('/list-chefs',ListChefs::class)->name('list-chefs');
    Route::post('/store-chef', StoreChef::class)->name('store-chef');
    Route::match(['post','put','path'],'/update-employee/{user}', UpdateChef::class)->name('update-chef');
    Route::delete('/delete-chef/{user}', DeleteChef::class)->name('delete-chef');

    Route::get('/list-recipes', ListRecipes::class)->name('list-recipes');
    Route::post('/store-recipe', StoreRecipe::class)->name('store-recipe');
    Route::match(['post','put','path'],'/update-recipe/{recipe}', UpdateRecipe::class)->name('update-recipe');
    Route::delete('/delete-recipe/{recipe}', DeleteRecipe::class)->name('delete-recipe');


    #

    Route::get('worker-meals-unconfirmed', WorkerUnconfirmedMeals::class)->name('worker-meals-unconfirmed');
    Route::post('order-meal/{recipe}', OrderMeal::class)->name('order-meal');

    Route::post('worker-meal-confirmation/{meal}',WorkerMealConfirmation::class)->name('worker-meal-confirmation');
    Route::post('chef-meal-confirmation', ChefMealConfirmation::class)->name('chef-meal-confirmation');

});



require __DIR__.'/auth.php';
require __DIR__.'/settings.php';
require __DIR__.'/frontend.php';
