# PHP_Laravel12_Create_Custome_Validation_Error



---

## Step 1: Install Laravel 12

This step is optional.  
If you have not created a Laravel application, run:

```
composer create-project laravel/laravel example-app
```

**Explanation:**  
This command creates a fresh Laravel 12 project with default configuration and folder structure.

---

## Step 2: Create Controller

Create a controller to handle form display and form submission.

```
php artisan make:controller FormController
```

Open the controller file:

```
app/Http/Controllers/FormController.php
```

Add the following code:

```php
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
     * @return View
     */
    public function create(): View
    {
        // Return the Blade view that contains the form
        return view('createUser');
    }

    /**
     * Store a newly created user in the database
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        // Validate request with custom error messages
        $validatedData = $request->validate(
            [
                'name' => 'required',
                'password' => 'required|min:5',
                'email' => 'required|email|unique:users',
            ],
            [
                'name.required' => 'Name field is required.',
                'password.required' => 'Password field is required.',
                'email.required' => 'Email field is required.',
                'email.email' => 'Email field must be email address.',
            ]
        );

        // Encrypt password before storing
        $validatedData['password'] = bcrypt($validatedData['password']);

        // Create new user record
        User::create($validatedData);

        // Redirect back with success message
        return back()->with('success', 'User created successfully.');
    }
}
```

**Explanation:**  
- `$request->validate()` validates incoming form data  
- Second array defines **custom error messages**  
- Password is encrypted using `bcrypt()`  
- User data is stored using Eloquent ORM  

---

## Step 3: Create Routes

Open `routes/web.php` and add:

```php
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FormController;

// Display user creation form
Route::get('users/create', [FormController::class, 'create']);

// Handle form submission
Route::post('users/create', [FormController::class, 'store'])
    ->name('users.store');

// Default home route
Route::get('/', function () {
    return view('welcome');
});
```

**Explanation:**  
- GET route shows the form  
- POST route processes form submission  
- Named route `users.store` is used in Blade form action  

---

## Step 4: Create Blade File

Create Blade file:

```
resources/views/createUser.blade.php
```

Add the following code:

```html
<!DOCTYPE html>
<html>
<head>
    <title>Laravel 12 Form Validation Example</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome (used in original document) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
</head>

<body>
<div class="container">
    <div class="card mt-5">

        <h3 class="card-header p-3">
            Laravel 12 Form Validation Example
        </h3>

        <div class="card-body">

            <!-- Success Message -->
            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Display all validation errors -->
            @if ($errors->any())
                <div class="alert alert-danger">
                    <strong>Whoops!</strong> There were some problems with your input.
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Form -->
            <form method="POST" action="{{ route('users.store') }}">
                @csrf

                <!-- Name -->
                <div class="mb-3">
                    <label>Name:</label>
                    <input type="text"
                           name="name"
                           class="form-control @error('name') is-invalid @enderror"
                           value="{{ old('name') }}">
                    @error('name')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Password -->
                <div class="mb-3">
                    <label>Password:</label>
                    <input type="password"
                           name="password"
                           class="form-control @error('password') is-invalid @enderror">
                    @if ($errors->has('password'))
                        <span class="text-danger">{{ $errors->first('password') }}</span>
                    @endif
                </div>

                <!-- Email -->
                <div class="mb-3">
                    <label>Email:</label>
                    <input type="text"
                           name="email"
                           class="form-control @error('email') is-invalid @enderror"
                           value="{{ old('email') }}">
                    @error('email')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Submit -->
                <button class="btn btn-success" type="submit">
                    Submit
                </button>
            </form>

        </div>
    </div>
</div>
</body>
</html>
```

**Explanation:**  
- `$errors->any()` displays all validation errors  
- `@error('field')` shows field-specific error  
- `old()` keeps previous input after validation failure  
- CSRF token protects form submission  

---

## Step 5: Run Laravel Application

Start the server:

```
php artisan serve
```

Open in browser:

```


http://localhost:8000/users/create
```
<img width="1599" height="803" alt="image" src="https://github.com/user-attachments/assets/fdf6b520-e4be-41e6-bb73-740ed0e23fef" />
