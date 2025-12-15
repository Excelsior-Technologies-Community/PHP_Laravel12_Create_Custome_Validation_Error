<!DOCTYPE html>
<html>
<head>
    <!-- Page title shown in browser tab -->
    <title>Laravel 12 Form Validation Example</title>
    
    <!-- Bootstrap 5 CSS for responsive styling and components -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    
    <!-- Font Awesome icons for visual enhancement -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
</head>
<body>
    <div class="container">
        <!-- Main card container with top margin -->
        <div class="card mt-5">
            <!-- Card header with icon and title -->
            <h3 class="card-header p-3">
                <i class="fa fa-star"></i> Laravel 12 Form Validation Example
            </h3>
            
            <div class="card-body">
                <!-- SUCCESS MESSAGE DISPLAY -->
                <!-- Shows when user creation is successful -->
                @if (session('success'))
                    <div class="alert alert-success" role="alert">
                        {{ session('success') }}
                    </div>
                @endif

                <!-- ERROR MESSAGES DISPLAY (Method 1: All errors at once) -->
                <!-- Shows ALL validation errors in a single alert box -->
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <strong>Whoops!</strong> There were some problems with your input.<br><br>
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
               
                <!-- MAIN FORM -->
                <form method="POST" action="{{ route('users.store') }}">
                    <!-- CSRF TOKEN PROTECTION -->
                    <!-- Laravel security feature to prevent cross-site request forgery -->
                    @csrf

                    <!-- NAME FIELD -->
                    <div class="mb-3">
                        <label class="form-label" for="inputName">Name:</label>
                        <input 
                            type="text" 
                            name="name" 
                            id="inputName"
                            class="form-control @error('name') is-invalid @enderror" 
                            placeholder="Name"
                            value="{{ old('name') }}">
                        
                        <!-- ERROR DISPLAY (Method 2: Field-specific error) -->
                        <!-- Shows only name field error below input -->
                        @error('name')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                 
                    <!-- PASSWORD FIELD -->
                    <div class="mb-3">
                        <label class="form-label" for="inputPassword">Password:</label>
                        <input 
                            type="password" 
                            name="password" 
                            id="inputPassword"
                            class="form-control @error('password') is-invalid @enderror" 
                            placeholder="Password">
                        
                        <!-- ERROR DISPLAY (Method 3: Alternative syntax) -->
                        <!-- Shows only password field error -->
                        @if ($errors->has('password'))
                            <span class="text-danger">{{ $errors->first('password') }}</span>
                        @endif
                    </div>
                
                    <!-- EMAIL FIELD -->
                    <div class="mb-3">
                        <label class="form-label" for="inputEmail">Email:</label>
                        <input 
                            type="text" 
                            name="email" 
                            id="inputEmail"
                            class="form-control @error('email') is-invalid @enderror" 
                            placeholder="Email"
                            value="{{ old('email') }}">
                        
                        <!-- ERROR DISPLAY (Method 2: Field-specific error) -->
                        @error('email')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                 
                    <!-- SUBMIT BUTTON -->
                    <div class="mb-3">
                        <button class="btn btn-success btn-submit" type="submit">
                            <i class="fa fa-save"></i> Submit
                        </button>
                    </div>
                </form>
            </div>
        </div>      
    </div>
</body>
</html>
