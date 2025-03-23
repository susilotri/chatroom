<?php

use Illuminate\Support\Facades\Route;

Route::get('/{any}', function () {
    return view('welcome'); // Pastikan ada file `resources/views/app.blade.php`
})->where('any', '.*');
