<?php

use App\Http\Controllers\FormController;
use Illuminate\Support\Facades\Route;

Route::get('users', [FormController::class, 'index'])
    ->name('users.index');

Route::get('users/create', [FormController::class, 'create'])
    ->name('users.create');

Route::post('users/create', [FormController::class, 'store'])
    ->name('users.store');

Route::get('users/{user}/edit', [FormController::class, 'edit'])
    ->name('users.edit');

Route::put('users/{user}', [FormController::class, 'update'])
    ->name('users.update');

Route::delete('users/{user}', [FormController::class, 'destroy'])
    ->name('users.destroy');

Route::post('users/validate', [FormController::class, 'validateAjax'])
    ->name('users.validate');

Route::post(
    'users/{user}/validate',
    [FormController::class, 'validateUpdateAjax']
)->name('users.validate.update');

Route::get('/', function () {
    return view('welcome');
});