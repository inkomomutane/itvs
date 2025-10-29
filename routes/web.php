<?php


use App\Http\Controllers\Employee\ListEmployees;
use App\Http\Controllers\Profile\ProfileInfoController;
use App\Http\Controllers\Profile\ProfileInfoUpdateController;
use App\Http\Controllers\Profile\UploadMemberPicture;
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
});





require __DIR__.'/auth.php';
require __DIR__.'/settings.php';
