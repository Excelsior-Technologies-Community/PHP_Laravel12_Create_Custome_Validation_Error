<?php

use App\Http\Controllers\FormController;
use Illuminate\Support\Facades\Route;

Route::get('users', [FormController::class, 'index'])->name('users.index');

Route::get('users/create', [FormController::class, 'create'])->name('users.create');

Route::post('users/create', [FormController::class, 'store'])->name('users.store');

Route::delete('users/{user}', [FormController::class, 'destroy'])->name('users.destroy');

Route::post('users/validate', [FormController::class, 'validateAjax'])->name('users.validate');

Route::get('/', function () {
    return view('welcome');
});
