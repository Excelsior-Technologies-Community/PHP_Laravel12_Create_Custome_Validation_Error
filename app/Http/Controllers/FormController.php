<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

/**
 * Controller handles user registration form operations
 * - Display user creation form
 * - Validate and store new user data
 */
class FormController extends Controller
{
    /**
     * Display the user creation form
     * 
     * This method returns the view that contains the HTML form
     * for creating a new user account.
     * 
     * @return View - Returns the 'createUser' blade template
     */
    public function create(): View
    {
        // Simply return the createUser blade view with empty form
        return view('createUser');
    }
     
    /**
     * Store a newly created user in database
     * 
     * This method:
     * 1. Validates incoming form data
     * 2. Hashes the password for security
     * 3. Creates new user record
     * 4. Redirects back with success message
     * 
     * @param Request $request - Contains form data (name, email, password)
     * @return RedirectResponse - Redirects back to form with status message
     */
    public function store(Request $request): RedirectResponse
    {
        // Validate form inputs with custom error messages
        $validatedData = $request->validate([
                // Name field is mandatory
                'name' => 'required',
                
                // Password must be at least 5 characters
                'password' => 'required|min:5',
                
                // Email must be valid format and unique in users table
                'email' => 'required|email|unique:users'
            ], [
                // Custom error messages for better UX
                'name.required' => 'Name field is required.',
                'password.required' => 'Password field is required.',
                'email.required' => 'Email field is required.',
                'email.email' => 'Email field must be email address.',
                // Note: Laravel auto-generates other validation errors
            ]);
        
        // Hash password using bcrypt for secure storage
        // Never store plain text passwords in database
        $validatedData['password'] = bcrypt($validatedData['password']);
        
        // Create new user record using Eloquent ORM
        // This automatically inserts data into users table
        $user = User::create($validatedData);
         
        // Redirect back to previous page (form) with success flash message
        // Message will be displayed using session flash data
        return back()->with('success', 'User created successfully.');
    }
}
