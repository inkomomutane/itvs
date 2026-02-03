<?php



Route::get('/ui', static function () {
    return \Inertia\Inertia::render('FrontPage');
})->name('ui.components');
