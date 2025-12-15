<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FormController;

/**
 * User Management Routes
 * Handles user creation form display and submission
 */
Route::get('users/create', [ FormController::class, 'create' ]);
// GET request - Displays the user creation form
// URL: /users/create
// Calls FormController@create method
// Returns createUser.blade.php view

Route::post('users/create', [ FormController::class, 'store' ])->name('users.store');
// POST request - Processes form submission
// URL: /users/create (same URL, different HTTP method)
// Calls FormController@store method
// Named route for easy reference in forms/links
// Use: route('users.store') in Blade templates

/**
 * Default Homepage Route
 * Laravel's default welcome page
 */
Route::get('/', function () {
    // Closure (anonymous function) returns welcome view
    // This is Laravel's default homepage
    // URL: /
    return view('welcome');
});
