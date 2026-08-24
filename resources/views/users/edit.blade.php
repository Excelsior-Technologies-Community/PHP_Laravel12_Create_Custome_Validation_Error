<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Edit User - Laravel 12 Custom Validation</title>

    <meta
        name="csrf-token"
        content="{{ csrf_token() }}"
    >

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
    />

    @vite(['resources/js/app.js'])

    <style>

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: linear-gradient(
                135deg,
                #667eea 0%,
                #764ba2 100%
            );

            min-height: 100vh;

            display: flex;

            align-items: center;

            justify-content: center;

            padding: 20px;
        }

        .card {
            border: none;

            border-radius: 1rem;

            box-shadow:
                0 20px 40px rgba(0, 0, 0, 0.15);

            overflow: hidden;
        }

        .card-header {
            background: linear-gradient(
                135deg,
                #1e3c72 0%,
                #2a5298 100%
            );

            color: white;

            font-weight: 600;

            font-size: 1.25rem;

            border: none;

            padding: 1.25rem 1.75rem;
        }

        .card-header i {
            color: #f8b400;

            margin-right: 0.5rem;
        }

        .card-body {
            padding: 2rem 2.5rem;

            background: #fafafa;
        }

        .form-field {
            position: relative;

            margin-bottom: 1.5rem;
        }

        .form-label {
            font-weight: 600;

            color: #333;

            margin-bottom: 0.4rem;

            font-size: 0.9rem;
        }

        .form-control {
            border-radius: 0.6rem;

            border: 2px solid #e0e0e0;

            padding: 0.7rem 1rem;

            font-size: 0.95rem;

            transition:
                border-color 0.2s ease,
                box-shadow 0.2s ease;
        }

        .form-control:focus {
            border-color: #667eea;

            box-shadow:
                0 0 0 3px
                rgba(102, 126, 234, 0.15);
        }

        .form-control.is-invalid {
            border-color: #ef4444;
        }

        .form-control.is-valid {
            border-color: #22c55e;
        }

        .error-banner {
            position: absolute;

            bottom: -1.6rem;

            left: 0;

            right: 0;

            background: #fee2e2;

            border: 1px solid #fca5a5;

            color: #991c1c;

            padding: 0.5rem 0.75rem;

            border-radius: 0.5rem;

            font-size: 0.8rem;

            opacity: 0;

            transform: translateY(0.6rem);

            transition: all 0.25s ease;

            pointer-events: none;

            z-index: 5;
        }

        .error-banner.show {
            opacity: 1;

            transform: translateY(0);
        }

        .error-banner::before {
            content: "\f057";

            font-family:
                "Font Awesome 6 Free";

            font-weight: 900;

            margin-right: 0.5rem;

            color: #dc2626;
        }

        .text-danger {
            font-size: 0.8rem;

            margin-top: 0.2rem;

            display: flex;

            align-items: center;

            gap: 0.3rem;

            color: #dc2626 !important;
        }

        .password-strength {
            margin-top: 0.5rem;

            padding: 0.75rem;

            background: #fff;

            border: 1px solid #e5e7eb;

            border-radius: 0.5rem;
        }

        .strength-bar-container {
            background: #e5e7eb;

            border-radius: 9999px;

            height: 0.5rem;

            overflow: hidden;

            margin-bottom: 0.3rem;
        }

        .strength-bar {
            height: 100%;

            width: 0%;

            border-radius: 9999px;

            transition: all 0.3s ease;
        }

        .strength-label {
            font-size: 0.8rem;

            font-weight: 600;

            display: flex;

            justify-content: space-between;
        }

        .hint {
            font-size: 0.78rem;

            color: #6b7280;

            margin-top: 0.2rem;

            display: flex;

            align-items: center;

            gap: 0.3rem;
        }

        /*
         * Validation status
         */
        .validation-status {
            background: #ffffff;

            border: 1px solid #e5e7eb;

            border-radius: 0.7rem;

            padding: 1rem;

            margin-bottom: 1.5rem;
        }

        .validation-status-title {
            font-weight: 700;

            color: #374151;

            margin-bottom: 0.75rem;
        }

        .validation-item {
            font-size: 0.82rem;

            display: flex;

            align-items: center;

            margin-bottom: 0.45rem;

            color: #6b7280;
        }

        .validation-item:last-child {
            margin-bottom: 0;
        }

        .validation-item i {
            width: 20px;

            margin-right: 0.35rem;
        }

        .validation-item.valid {
            color: #15803d;
        }

        .validation-item.invalid {
            color: #dc2626;
        }

        .validation-item.pending {
            color: #6b7280;
        }

        .validation-complete {
            display: none;

            margin-top: 0.75rem;

            padding: 0.6rem 0.75rem;

            border-radius: 0.5rem;

            background: #dcfce7;

            color: #166534;

            font-size: 0.82rem;

            font-weight: 600;
        }

        .btn-submit {
            border-radius: 0.6rem;

            padding: 0.7rem 2rem;

            font-weight: 600;

            font-size: 0.95rem;
        }

    </style>

</head>

<body>

<div
    class="container"
    style="max-width: 650px;"
>

    <div class="card">

        <div class="card-header">

            <div class="d-flex align-items-center">

                <a
                    href="{{ route('users.index') }}"
                    class="btn btn-sm btn-outline-light me-2"
                    style="border-radius: 0.4rem;"
                >
                    <i class="fa fa-arrow-left"></i>
                </a>

                <i class="fa fa-user-pen"></i>

                Edit User

            </div>

        </div>

        <div class="card-body">

            @if (session('success'))

                <div
                    class="alert alert-success alert-dismissible fade show"
                    role="alert"
                >

                    <i class="fa fa-check-circle me-2"></i>

                    {{ session('success') }}

                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="alert"
                    ></button>

                </div>

            @endif


            @if ($errors->any())

                <div
                    class="alert alert-danger alert-dismissible fade show"
                    role="alert"
                >

                    <strong>
                        <i class="fa fa-exclamation-triangle me-2"></i>
                        Please fix the following:
                    </strong>

                    <ul class="mb-0 mt-2">

                        @foreach ($errors->all() as $error)

                            <li>
                                {{ $error }}
                            </li>

                        @endforeach

                    </ul>

                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="alert"
                    ></button>

                </div>

            @endif


            {{-- Validation Status --}}

            <div
                class="validation-status"
                id="validation-status"
            >

                <div class="validation-status-title">

                    <i class="fa fa-clipboard-check me-1"></i>

                    Validation Status

                </div>


                <div
                    class="validation-item pending"
                    data-status-field="name"
                >

                    <i class="fa fa-circle"></i>

                    Name

                </div>


                <div
                    class="validation-item pending"
                    data-status-field="email"
                >

                    <i class="fa fa-circle"></i>

                    Email

                </div>


                <div
                    class="validation-item pending"
                    data-status-field="password"
                >

                    <i class="fa fa-circle"></i>

                    New password

                </div>


                <div
                    class="validation-item pending"
                    data-status-field="password_confirmation"
                >

                    <i class="fa fa-circle"></i>

                    Password confirmation

                </div>


                <div
                    id="validation-complete"
                    class="validation-complete"
                >

                    <i class="fa fa-circle-check me-1"></i>

                    All entered fields are valid.

                </div>

            </div>


            <form
                id="user-form"
                method="POST"
                action="{{ route('users.update', $user) }}"
                data-validate-url="{{ route('users.validate.update', $user) }}"
            >

                @csrf

                @method('PUT')


                {{-- Name --}}

                <div class="form-field">

                    <label
                        class="form-label"
                        for="inputName"
                    >

                        <i class="fa fa-user me-1 text-muted"></i>

                        Name

                    </label>

                    <input
                        type="text"
                        name="name"
                        id="inputName"
                        class="form-control @error('name') is-invalid @enderror"
                        placeholder="Enter full name"
                        value="{{ old('name', $user->name) }}"
                    >

                    @error('name')

                        <div class="text-danger">

                            <i class="fa fa-info-circle"></i>

                            {{ $message }}

                        </div>

                    @enderror

                </div>


                {{-- Email --}}

                <div class="form-field">

                    <label
                        class="form-label"
                        for="inputEmail"
                    >

                        <i class="fa fa-envelope me-1 text-muted"></i>

                        Email

                    </label>

                    <input
                        type="email"
                        name="email"
                        id="inputEmail"
                        class="form-control @error('email') is-invalid @enderror"
                        placeholder="you@example.com"
                        value="{{ old('email', $user->email) }}"
                    >

                    @error('email')

                        <div class="text-danger">

                            <i class="fa fa-info-circle"></i>

                            {{ $message }}

                        </div>

                    @enderror

                </div>


                {{-- Password --}}

                <div class="form-field">

                    <label
                        class="form-label"
                        for="inputPassword"
                    >

                        <i class="fa fa-lock me-1 text-muted"></i>

                        New Password

                    </label>

                    <input
                        type="password"
                        name="password"
                        id="inputPassword"
                        class="form-control @error('password') is-invalid @enderror"
                        placeholder="Leave empty to keep current password"
                    >

                    <div
                        id="password-strength-meter"
                        style="display: none;"
                        class="password-strength"
                    >

                        <div class="strength-bar-container">

                            <div
                                id="password-strength-bar"
                                class="strength-bar"
                            ></div>

                        </div>

                        <div class="strength-label">

                            <span id="password-strength-text"></span>

                        </div>

                        <div
                            id="password-criteria"
                            class="mt-2"
                        ></div>

                    </div>

                    @error('password')

                        <div class="text-danger">

                            <i class="fa fa-info-circle"></i>

                            {{ $message }}

                        </div>

                    @enderror

                    <div class="hint">

                        <i class="fa fa-info-circle"></i>

                        Leave empty to keep the current password.

                    </div>

                </div>


                {{-- Confirm Password --}}

                <div class="form-field">

                    <label
                        class="form-label"
                        for="inputConfirmPassword"
                    >

                        <i class="fa fa-lock me-1 text-muted"></i>

                        Confirm New Password

                    </label>

                    <input
                        type="password"
                        name="password_confirmation"
                        id="inputConfirmPassword"
                        class="form-control @error('password_confirmation') is-invalid @enderror"
                        placeholder="Confirm new password"
                    >

                    @error('password_confirmation')

                        <div class="text-danger">

                            <i class="fa fa-info-circle"></i>

                            {{ $message }}

                        </div>

                    @enderror

                </div>


                <div class="d-grid">

                    <button
                        class="btn btn-success btn-submit"
                        type="submit"
                    >

                        <i class="fa fa-save"></i>

                        Update User

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>