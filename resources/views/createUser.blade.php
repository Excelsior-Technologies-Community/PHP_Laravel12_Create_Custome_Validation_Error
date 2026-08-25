<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>Create User - Laravel 12 Custom Validation</title>

    <meta
        name="csrf-token"
        content="{{ csrf_token() }}">

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet">

    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />

    @vite(['resources/js/app.js'])

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;

            background:
                radial-gradient(circle at top left,
                    rgba(255, 255, 255, .18),
                    transparent 30%),
                linear-gradient(135deg,
                    #667eea 0%,
                    #764ba2 100%);

            display: flex;
            align-items: center;
            justify-content: center;

            padding: 30px 20px;

            font-family:
                Inter,
                system-ui,
                -apple-system,
                BlinkMacSystemFont,
                "Segoe UI",
                sans-serif;
        }

        .main-wrapper {
            width: 100%;
            max-width: 650px;
        }

        .card {
            border: 0;
            border-radius: 22px;

            overflow: hidden;

            box-shadow:
                0 25px 60px rgba(0, 0, 0, .20);

            background: #fff;
        }

        .card-header {
            padding: 22px 26px;

            background:
                linear-gradient(135deg,
                    #1e3c72,
                    #2a5298);

            color: white;

            border: 0;
        }

        .header-title {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .header-icon {
            width: 42px;
            height: 42px;

            display: flex;
            align-items: center;
            justify-content: center;

            border-radius: 12px;

            background:
                rgba(255, 255, 255, .15);

            color: #ffd166;
        }

        .header-title h4 {
            margin: 0;
            font-weight: 700;
        }

        .header-title small {
            opacity: .75;
        }

        .card-body {
            padding: 30px;
            background: #fff;
        }

        .form-field {
            margin-bottom: 22px;
        }

        .form-label {
            font-weight: 700;
            color: #374151;
            margin-bottom: 8px;
        }

        .form-label i {
            color: #667eea;
        }

        .form-control {
            min-height: 48px;

            border: 2px solid #e5e7eb;

            border-radius: 12px;

            padding:
                10px 14px;

            font-size: .95rem;

            transition:
                .2s ease;
        }

        .form-control:focus {
            border-color: #667eea;

            box-shadow:
                0 0 0 4px rgba(102, 126, 234, .12);
        }

        .form-control.is-invalid {
            border-color: #ef4444;
        }

        .form-control.is-valid {
            border-color: #22c55e;
        }

        .input-group .form-control {
            border-radius: 12px 0 0 12px;
        }

        .input-group .btn {
            border-radius: 0 12px 12px 0;
            min-width: 50px;
        }

        .text-danger {
            color: #dc2626 !important;

            font-size: .82rem;

            margin-top: 7px;

            display: block;
        }

        .field-success {
            color: #15803d;

            font-size: .82rem;

            margin-top: 7px;

            display: none;
        }

        .duplicate-warning {
            margin-top: 9px;

            padding: 10px 13px;

            border-radius: 10px;

            font-size: .82rem;

            background: #fff7ed;

            border: 1px solid #fed7aa;

            color: #9a3412;
        }

        .password-box {
            margin-top: 12px;

            padding: 16px;

            border-radius: 14px;

            background: #f8fafc;

            border: 1px solid #e5e7eb;
        }

        .password-title {
            font-size: .85rem;

            font-weight: 700;

            color: #374151;

            margin-bottom: 10px;
        }

        .password-rule {
            font-size: .82rem;

            margin-bottom: 7px;

            color: #6b7280;
        }

        .password-rule.valid {
            color: #15803d;
            font-weight: 600;
        }

        .password-rule.invalid {
            color: #dc2626;
        }

        .password-strength {
            height: 7px;

            background: #e5e7eb;

            border-radius: 50px;

            overflow: hidden;

            margin-top: 13px;
        }

        .password-strength-bar {
            height: 100%;

            width: 0%;

            border-radius: 50px;

            transition: all .3s ease;
        }

        .strength-text {
            margin-top: 6px;

            font-size: .78rem;

            font-weight: 700;

            color: #6b7280;
        }

        .hint {
            color: #6b7280;

            font-size: .78rem;

            margin-top: 7px;
        }

        .submit-btn {
            min-height: 50px;

            border: 0;

            border-radius: 12px;

            font-weight: 700;

            background:
                linear-gradient(135deg,
                    #10b981,
                    #059669);

            transition: .2s ease;
        }

        .submit-btn:hover {
            transform: translateY(-1px);

            box-shadow:
                0 8px 20px rgba(16, 185, 129, .25);
        }

        .submit-btn:disabled {
            opacity: .7;
            transform: none;
        }

        .back-btn {
            border-radius: 9px;
        }

        .validation-loader {
            display: none;

            color: #667eea;

            font-size: .78rem;

            margin-top: 6px;
        }

        .validation-loader.show {
            display: block;
        }

        .alert {
            border-radius: 12px;
        }

        @media(max-width:576px) {

            .card-body {
                padding: 20px;
            }

            .card-header {
                padding: 18px;
            }

        }
    </style>

</head>

<body>

    <div class="main-wrapper">

        <div class="card">

            {{-- HEADER --}}
            <div class="card-header">

                <div class="d-flex align-items-center justify-content-between">

                    <div class="header-title">

                        <a
                            href="{{ route('users.index') }}"
                            class="btn btn-sm btn-outline-light back-btn">
                            <i class="fa fa-arrow-left"></i>
                        </a>

                        <div class="header-icon">
                            <i class="fa fa-user-plus"></i>
                        </div>

                        <div>

                            <h4>Create User</h4>

                            <small>
                                Secure user registration
                            </small>

                        </div>

                    </div>

                </div>

            </div>


            {{-- BODY --}}
            <div class="card-body">

                @if(session('success'))

                <div class="alert alert-success">

                    <i class="fa fa-check-circle me-2"></i>

                    {{ session('success') }}

                </div>

                @endif


                @if($errors->any())

                <div class="alert alert-danger">

                    <strong>
                        <i class="fa fa-triangle-exclamation me-1"></i>
                        Please fix the following:
                    </strong>

                    <ul class="mb-0 mt-2">

                        @foreach($errors->all() as $error)

                        <li>
                            {{ $error }}
                        </li>

                        @endforeach

                    </ul>

                </div>

                @endif


                <form
                    id="user-form"
                    method="POST"
                    action="{{ route('users.store') }}"
                    data-validate-url="{{ route('users.validate') }}">

                    @csrf


                    {{-- NAME --}}
                    <div class="form-field">

                        <label
                            class="form-label"
                            for="inputName">

                            <i class="fa fa-user me-1"></i>

                            Full Name

                        </label>

                        <input
                            type="text"
                            name="name"
                            id="inputName"
                            class="form-control"
                            placeholder="Enter your full name"
                            value="{{ old('name') }}"
                            autocomplete="name">

                        <div
                            id="name-error"
                            class="text-danger"></div>

                        <div
                            id="name-success"
                            class="field-success">
                            <i class="fa fa-circle-check"></i>
                            Name is valid.
                        </div>

                        <div
                            id="name-warning"
                            class="duplicate-warning d-none">
                            <i class="fa fa-triangle-exclamation me-1"></i>

                            Another user with this name already exists.
                        </div>

                    </div>


                    {{-- EMAIL --}}
                    <div class="form-field">

                        <label
                            class="form-label"
                            for="inputEmail">

                            <i class="fa fa-envelope me-1"></i>

                            Email Address

                        </label>

                        <input
                            type="email"
                            name="email"
                            id="inputEmail"
                            class="form-control"
                            placeholder="you@example.com"
                            value="{{ old('email') }}"
                            autocomplete="email">

                        <div
                            id="email-error"
                            class="text-danger"></div>

                        <div
                            id="email-success"
                            class="field-success">
                            <i class="fa fa-circle-check"></i>
                            Email is available.
                        </div>

                    </div>


                    {{-- PASSWORD --}}
                    <div class="form-field">

                        <label
                            class="form-label"
                            for="inputPassword">

                            <i class="fa fa-lock me-1"></i>

                            Password

                        </label>


                        <div class="input-group">

                            <input
                                type="password"
                                name="password"
                                id="inputPassword"
                                class="form-control"
                                placeholder="Create a strong password"
                                autocomplete="new-password">

                            <button
                                type="button"
                                class="btn btn-outline-secondary"
                                id="togglePassword">

                                <i
                                    class="fa fa-eye"
                                    id="passwordIcon"></i>

                            </button>

                        </div>


                        {{-- PASSWORD CHECKLIST --}}
                        <div class="password-box">

                            <div class="password-title">

                                <i class="fa fa-shield-halved me-1"></i>

                                Password requirements

                            </div>


                            <div
                                class="password-rule"
                                id="rule-length">
                                ❌ At least 8 characters
                            </div>

                            <div
                                class="password-rule"
                                id="rule-uppercase">
                                ❌ One uppercase letter
                            </div>

                            <div
                                class="password-rule"
                                id="rule-lowercase">
                                ❌ One lowercase letter
                            </div>

                            <div
                                class="password-rule"
                                id="rule-number">
                                ❌ One number
                            </div>

                            <div
                                class="password-rule"
                                id="rule-special">
                                ❌ One special character
                            </div>

                            <div
                                class="password-rule"
                                id="rule-common">
                                ❌ Not a common password
                            </div>


                            <div class="password-strength">

                                <div
                                    id="password-strength-bar"
                                    class="password-strength-bar"></div>

                            </div>

                            <div
                                id="strength-text"
                                class="strength-text">
                                Password strength: —
                            </div>

                        </div>


                        <div
                            id="password-error"
                            class="text-danger"></div>

                        <div class="hint">

                            <i class="fa fa-circle-info me-1"></i>

                            Use uppercase, lowercase, number and special character.

                        </div>

                    </div>


                    {{-- CONFIRM PASSWORD --}}
                    <div class="form-field">

                        <label
                            class="form-label"
                            for="inputConfirmPassword">

                            <i class="fa fa-lock me-1"></i>

                            Confirm Password

                        </label>


                        <div class="input-group">

                            <input
                                type="password"
                                name="password_confirmation"
                                id="inputConfirmPassword"
                                class="form-control"
                                placeholder="Confirm your password"
                                autocomplete="new-password">

                            <button
                                type="button"
                                class="btn btn-outline-secondary"
                                id="toggleConfirmPassword">

                                <i
                                    class="fa fa-eye"
                                    id="confirmIcon"></i>

                            </button>

                        </div>


                        <div
                            id="confirm-error"
                            class="text-danger"></div>

                        <div
                            id="confirm-success"
                            class="field-success">
                            <i class="fa fa-circle-check"></i>
                            Passwords match.
                        </div>

                    </div>


                    {{-- SUBMIT --}}
                    <div class="d-grid">

                        <button
                            class="btn btn-success submit-btn"
                            type="submit"
                            id="submitButton">

                            <i class="fa fa-user-plus me-1"></i>

                            Create User

                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>


    <script
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>


    <script>
        document.addEventListener(
            'DOMContentLoaded',
            function() {

                const form =
                    document.getElementById('user-form');

                const nameInput =
                    document.getElementById('inputName');

                const emailInput =
                    document.getElementById('inputEmail');

                const passwordInput =
                    document.getElementById('inputPassword');

                const confirmInput =
                    document.getElementById(
                        'inputConfirmPassword'
                    );

                const submitButton =
                    document.getElementById(
                        'submitButton'
                    );


                let validationTimer = null;


                /*
                |--------------------------------------------------------------------------
                | Password visibility
                |--------------------------------------------------------------------------
                */

                document
                    .getElementById('togglePassword')
                    .addEventListener(
                        'click',
                        function() {

                            if (
                                passwordInput.type ===
                                'password'
                            ) {

                                passwordInput.type =
                                    'text';

                                document
                                    .getElementById(
                                        'passwordIcon'
                                    )
                                    .className =
                                    'fa fa-eye-slash';

                            } else {

                                passwordInput.type =
                                    'password';

                                document
                                    .getElementById(
                                        'passwordIcon'
                                    )
                                    .className =
                                    'fa fa-eye';
                            }

                        }
                    );


                document
                    .getElementById(
                        'toggleConfirmPassword'
                    )
                    .addEventListener(
                        'click',
                        function() {

                            if (
                                confirmInput.type ===
                                'password'
                            ) {

                                confirmInput.type =
                                    'text';

                                document
                                    .getElementById(
                                        'confirmIcon'
                                    )
                                    .className =
                                    'fa fa-eye-slash';

                            } else {

                                confirmInput.type =
                                    'password';

                                document
                                    .getElementById(
                                        'confirmIcon'
                                    )
                                    .className =
                                    'fa fa-eye';
                            }

                        }
                    );


                /*
                |--------------------------------------------------------------------------
                | Password checklist
                |--------------------------------------------------------------------------
                */

                function updatePasswordChecklist() {

                    const password =
                        passwordInput.value;

                    const checks = {

                        length: password.length >= 8,

                        uppercase: /[A-Z]/.test(password),

                        lowercase: /[a-z]/.test(password),

                        number: /[0-9]/.test(password),

                        special: /[\W_]/.test(password),

                        common:
                            ![
                                'password',
                                '123456',
                                '12345678',
                                'qwerty',
                                'abc123',
                                'password1',
                                'admin',
                                'welcome',
                                'password123'
                            ].includes(
                                password.toLowerCase()
                            )
                    };


                    updateRule(
                        'rule-length',
                        checks.length,
                        'At least 8 characters'
                    );

                    updateRule(
                        'rule-uppercase',
                        checks.uppercase,
                        'One uppercase letter'
                    );

                    updateRule(
                        'rule-lowercase',
                        checks.lowercase,
                        'One lowercase letter'
                    );

                    updateRule(
                        'rule-number',
                        checks.number,
                        'One number'
                    );

                    updateRule(
                        'rule-special',
                        checks.special,
                        'One special character'
                    );

                    updateRule(
                        'rule-common',
                        password.length > 0 &&
                        checks.common,
                        'Not a common password'
                    );


                    const passed =
                        Object.values(checks)
                        .filter(Boolean)
                        .length;


                    const bar =
                        document.getElementById(
                            'password-strength-bar'
                        );

                    const text =
                        document.getElementById(
                            'strength-text'
                        );


                    if (!password) {

                        bar.style.width = '0%';

                        text.textContent =
                            'Password strength: —';

                        return;
                    }


                    const percentage =
                        Math.round(
                            (passed / 6) * 100
                        );

                    bar.style.width =
                        percentage + '%';


                    if (passed <= 2) {

                        text.textContent =
                            'Password strength: Weak';

                    } else if (passed <= 4) {

                        text.textContent =
                            'Password strength: Medium';

                    } else if (passed === 5) {

                        text.textContent =
                            'Password strength: Strong';

                    } else {

                        text.textContent =
                            'Password strength: Excellent';
                    }

                }


                function updateRule(
                    id,
                    valid,
                    text
                ) {

                    const element =
                        document.getElementById(id);

                    element.classList.remove(
                        'valid',
                        'invalid'
                    );

                    if (valid) {

                        element.classList.add(
                            'valid'
                        );

                        element.innerHTML =
                            `✅ ${text}`;

                    } else {

                        element.classList.add(
                            'invalid'
                        );

                        element.innerHTML =
                            `❌ ${text}`;
                    }
                }


                passwordInput.addEventListener(
                    'input',
                    function() {

                        updatePasswordChecklist();

                        validateWithAjax();
                    }
                );


                confirmInput.addEventListener(
                    'input',
                    function() {

                        checkPasswordConfirmation();

                        validateWithAjax();
                    }
                );


                function checkPasswordConfirmation() {

                    const error =
                        document.getElementById(
                            'confirm-error'
                        );

                    const success =
                        document.getElementById(
                            'confirm-success'
                        );


                    if (!confirmInput.value) {

                        error.innerHTML = '';

                        success.style.display =
                            'none';

                        return;
                    }


                    if (
                        confirmInput.value ===
                        passwordInput.value
                    ) {

                        confirmInput.classList.remove(
                            'is-invalid'
                        );

                        confirmInput.classList.add(
                            'is-valid'
                        );

                        error.innerHTML = '';

                        success.style.display =
                            'block';

                    } else {

                        confirmInput.classList.remove(
                            'is-valid'
                        );

                        confirmInput.classList.add(
                            'is-invalid'
                        );

                        success.style.display =
                            'none';

                        error.innerHTML =
                            '<i class="fa fa-info-circle"></i> Password confirmation does not match.';
                    }
                }


                /*
                |--------------------------------------------------------------------------
                | AJAX validation
                |--------------------------------------------------------------------------
                */

                function validateWithAjax() {

                    clearTimeout(
                        validationTimer
                    );

                    validationTimer =
                        setTimeout(
                            sendValidationRequest,
                            500
                        );
                }


                async function sendValidationRequest() {

                    const formData =
                        new FormData(form);


                    try {

                        const response =
                            await fetch(
                                form.dataset.validateUrl, {
                                    method: 'POST',

                                    headers: {
                                        'X-CSRF-TOKEN': document
                                            .querySelector(
                                                'meta[name="csrf-token"]'
                                            )
                                            .content,

                                        'Accept': 'application/json'
                                    },

                                    body: formData
                                }
                            );


                        const data =
                            await response.json();


                        clearAjaxErrors();


                        /*
                        |--------------------------------------------------------------------------
                        | Duplicate name
                        |--------------------------------------------------------------------------
                        */

                        const warning =
                            document.getElementById(
                                'name-warning'
                            );


                        if (
                            data.duplicate_name
                        ) {

                            warning.classList.remove(
                                'd-none'
                            );

                        } else {

                            warning.classList.add(
                                'd-none'
                            );
                        }


                        /*
                        |--------------------------------------------------------------------------
                        | Errors
                        |--------------------------------------------------------------------------
                        */

                        if (data.errors) {

                            Object.keys(
                                data.errors
                            ).forEach(
                                field => {

                                    const message =
                                        data.errors[
                                            field
                                        ][0];

                                    showFieldError(
                                        field,
                                        message
                                    );

                                }
                            );

                        }


                        /*
                        |--------------------------------------------------------------------------
                        | Success indicators
                        |--------------------------------------------------------------------------
                        */

                        if (
                            !data.errors?.name &&
                            nameInput.value.length >= 2
                        ) {

                            nameInput.classList.add(
                                'is-valid'
                            );

                            document
                                .getElementById(
                                    'name-success'
                                )
                                .style.display =
                                'block';
                        }


                        if (
                            !data.errors?.email &&
                            emailInput.value
                        ) {

                            emailInput.classList.add(
                                'is-valid'
                            );

                            document
                                .getElementById(
                                    'email-success'
                                )
                                .style.display =
                                'block';
                        }

                    } catch (error) {

                        console.error(
                            'AJAX validation error:',
                            error
                        );

                    }

                }


                function showFieldError(
                    field,
                    message
                ) {

                    let input;

                    let errorElement;


                    if (field === 'name') {

                        input = nameInput;

                        errorElement =
                            document.getElementById(
                                'name-error'
                            );

                    } else if (
                        field === 'email'
                    ) {

                        input = emailInput;

                        errorElement =
                            document.getElementById(
                                'email-error'
                            );

                    } else if (
                        field === 'password'
                    ) {

                        input = passwordInput;

                        errorElement =
                            document.getElementById(
                                'password-error'
                            );

                    } else if (
                        field ===
                        'password_confirmation'
                    ) {

                        input = confirmInput;

                        errorElement =
                            document.getElementById(
                                'confirm-error'
                            );

                    }


                    if (
                        input &&
                        errorElement
                    ) {

                        input.classList.add(
                            'is-invalid'
                        );

                        input.classList.remove(
                            'is-valid'
                        );

                        errorElement.innerHTML =
                            `<i class="fa fa-info-circle"></i> ${message}`;
                    }

                }


                function clearAjaxErrors() {

                    document
                        .querySelectorAll(
                            '.text-danger'
                        )
                        .forEach(
                            element => {
                                element.innerHTML =
                                    '';
                            }
                        );

                    document
                        .querySelectorAll(
                            '.form-control'
                        )
                        .forEach(
                            element => {

                                element.classList.remove(
                                    'is-invalid',
                                    'is-valid'
                                );

                            }
                        );


                    document
                        .getElementById(
                            'name-success'
                        )
                        .style.display =
                        'none';

                    document
                        .getElementById(
                            'email-success'
                        )
                        .style.display =
                        'none';
                }


                /*
                |--------------------------------------------------------------------------
                | Input events
                |--------------------------------------------------------------------------
                */

                nameInput.addEventListener(
                    'input',
                    validateWithAjax
                );

                emailInput.addEventListener(
                    'input',
                    validateWithAjax
                );


                /*
                |--------------------------------------------------------------------------
                | Submit
                |--------------------------------------------------------------------------
                */

                form.addEventListener(
                    'submit',
                    async function(event) {

                        event.preventDefault();

                        submitButton.disabled =
                            true;

                        submitButton.innerHTML =
                            '<i class="fa fa-spinner fa-spin me-1"></i> Validating...';


                        checkPasswordConfirmation();

                        await sendValidationRequest();


                        if (
                            document.querySelector(
                                '.is-invalid'
                            )
                        ) {

                            submitButton.disabled =
                                false;

                            submitButton.innerHTML =
                                '<i class="fa fa-user-plus me-1"></i> Create User';

                            return;
                        }


                        submitButton.innerHTML =
                            '<i class="fa fa-spinner fa-spin me-1"></i> Creating...';

                        form.submit();

                    }
                );


                updatePasswordChecklist();

            }
        );
    </script>

</body>

</html>