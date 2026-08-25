<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>
        Edit User - Laravel 12 Custom Validation
    </title>

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

            padding: 30px 20px;

            background:
                radial-gradient(circle at top left,
                    rgba(255, 255, 255, .18),
                    transparent 30%),
                linear-gradient(135deg,
                    #667eea,
                    #764ba2);

            display: flex;

            align-items: center;

            justify-content: center;

            font-family:
                Inter,
                system-ui,
                sans-serif;
        }

        .main-wrapper {
            width: 100%;
            max-width: 680px;
        }

        .card {
            border: 0;

            border-radius: 22px;

            overflow: hidden;

            box-shadow:
                0 25px 60px rgba(0, 0, 0, .20);
        }

        .card-header {
            padding: 22px 25px;

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
            opacity: .7;
        }

        .card-body {
            padding: 30px;

            background: white;
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

            padding: 10px 14px;

            transition: .2s ease;
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
        }

        .text-danger {
            color: #dc2626 !important;

            font-size: .82rem;

            margin-top: 7px;
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

            background: #fff7ed;

            border: 1px solid #fed7aa;

            color: #9a3412;

            font-size: .82rem;
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

            transition: .3s ease;
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

        .validation-status {
            padding: 16px;

            margin-bottom: 25px;

            border-radius: 15px;

            background: #f8fafc;

            border: 1px solid #e5e7eb;
        }

        .validation-title {
            font-weight: 800;

            color: #374151;

            margin-bottom: 12px;
        }

        .validation-item {
            display: flex;

            align-items: center;

            gap: 8px;

            font-size: .82rem;

            margin-bottom: 8px;

            color: #6b7280;
        }

        .validation-item:last-child {
            margin-bottom: 0;
        }

        .validation-item.valid {
            color: #15803d;
        }

        .validation-item.invalid {
            color: #dc2626;
        }

        .validation-item i {
            width: 17px;
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
        }

        @media(max-width:576px) {

            .card-body {
                padding: 20px;
            }

        }
    </style>

</head>

<body>

    <div class="main-wrapper">

        <div class="card">

            {{-- HEADER --}}
            <div class="card-header">

                <div class="header-title">

                    <a
                        href="{{ route('users.index') }}"
                        class="btn btn-sm btn-outline-light">

                        <i class="fa fa-arrow-left"></i>

                    </a>

                    <div class="header-icon">

                        <i class="fa fa-user-pen"></i>

                    </div>

                    <div>

                        <h4>Edit User</h4>

                        <small>
                            Update user information securely
                        </small>

                    </div>

                </div>

            </div>


            <div class="card-body">


                @if(session('success'))

                <div class="alert alert-success">

                    <i class="fa fa-circle-check me-2"></i>

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


                {{-- VALIDATION STATUS --}}
                <div class="validation-status">

                    <div class="validation-title">

                        <i
                            class="fa fa-clipboard-check me-1"></i>

                        Validation Status

                    </div>


                    <div
                        class="validation-item"
                        id="status-name">

                        <i class="fa fa-circle"></i>

                        Name

                    </div>


                    <div
                        class="validation-item"
                        id="status-email">

                        <i class="fa fa-circle"></i>

                        Email

                    </div>


                    <div
                        class="validation-item"
                        id="status-password">

                        <i class="fa fa-circle"></i>

                        New password

                    </div>


                    <div
                        class="validation-item"
                        id="status-confirm">

                        <i class="fa fa-circle"></i>

                        Password confirmation

                    </div>

                </div>


                <form
                    id="user-form"
                    method="POST"
                    action="{{ route('users.update', $user) }}"
                    data-validate-url="{{ route('users.validate.update', $user) }}">

                    @csrf

                    @method('PUT')


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
                            value="{{ old('name', $user->name) }}"
                            autocomplete="name">

                        <div
                            id="name-error"
                            class="text-danger"></div>

                        <div
                            id="name-warning"
                            class="duplicate-warning d-none">

                            <i
                                class="fa fa-triangle-exclamation me-1"></i>

                            Another user already has this name.

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
                            value="{{ old('email', $user->email) }}"
                            autocomplete="email">

                        <div
                            id="email-error"
                            class="text-danger"></div>

                    </div>


                    {{-- PASSWORD --}}
                    <div class="form-field">

                        <label
                            class="form-label"
                            for="inputPassword">

                            <i class="fa fa-lock me-1"></i>

                            New Password

                        </label>


                        <div class="input-group">

                            <input
                                type="password"
                                name="password"
                                id="inputPassword"
                                class="form-control"
                                placeholder="Leave empty to keep current password"
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

                                <i
                                    class="fa fa-shield-halved me-1"></i>

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

                            Leave empty if you don't want to change the password.

                        </div>

                    </div>


                    {{-- CONFIRM PASSWORD --}}
                    <div class="form-field">

                        <label
                            class="form-label"
                            for="inputConfirmPassword">

                            <i class="fa fa-lock me-1"></i>

                            Confirm New Password

                        </label>


                        <div class="input-group">

                            <input
                                type="password"
                                name="password_confirmation"
                                id="inputConfirmPassword"
                                class="form-control"
                                placeholder="Confirm new password"
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

                    </div>


                    {{-- SUBMIT --}}
                    <div class="d-grid">

                        <button
                            type="submit"
                            class="btn btn-success submit-btn"
                            id="submitButton">

                            <i class="fa fa-save me-1"></i>

                            Update User

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
                    document.getElementById(
                        'user-form'
                    );

                const nameInput =
                    document.getElementById(
                        'inputName'
                    );

                const emailInput =
                    document.getElementById(
                        'inputEmail'
                    );

                const passwordInput =
                    document.getElementById(
                        'inputPassword'
                    );

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
                    .getElementById(
                        'togglePassword'
                    )
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


                    if (!password) {

                        resetPasswordChecklist();

                        return;
                    }


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


                    bar.style.width =
                        Math.round(
                            (passed / 6) * 100
                        ) + '%';


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


                function resetPasswordChecklist() {

                    const rules = [
                        [
                            'rule-length',
                            'At least 8 characters'
                        ],
                        [
                            'rule-uppercase',
                            'One uppercase letter'
                        ],
                        [
                            'rule-lowercase',
                            'One lowercase letter'
                        ],
                        [
                            'rule-number',
                            'One number'
                        ],
                        [
                            'rule-special',
                            'One special character'
                        ],
                        [
                            'rule-common',
                            'Not a common password'
                        ]
                    ];


                    rules.forEach(
                        rule => {

                            const element =
                                document.getElementById(
                                    rule[0]
                                );

                            element.classList.remove(
                                'valid',
                                'invalid'
                            );

                            element.innerHTML =
                                `❌ ${rule[1]}`;

                        }
                    );


                    document
                        .getElementById(
                            'password-strength-bar'
                        )
                        .style.width =
                        '0%';


                    document
                        .getElementById(
                            'strength-text'
                        )
                        .textContent =
                        'Password strength: —';

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

                        checkConfirmation();

                        validateWithAjax();

                    }
                );


                function checkConfirmation() {

                    if (!confirmInput.value) {

                        setStatus(
                            'status-confirm',
                            'pending',
                            'Password confirmation'
                        );

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

                        document
                            .getElementById(
                                'confirm-error'
                            )
                            .innerHTML = '';


                        setStatus(
                            'status-confirm',
                            'valid',
                            'Password confirmation'
                        );

                    } else {

                        confirmInput.classList.remove(
                            'is-valid'
                        );

                        confirmInput.classList.add(
                            'is-invalid'
                        );


                        document
                            .getElementById(
                                'confirm-error'
                            )
                            .innerHTML =
                            '<i class="fa fa-info-circle"></i> Password confirmation does not match.';


                        setStatus(
                            'status-confirm',
                            'invalid',
                            'Password confirmation'
                        );

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


                        clearErrors();


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

                                    showError(
                                        field,
                                        data.errors[field][0]
                                    );

                                }
                            );

                        }


                        updateStatuses(
                            data.errors || {}
                        );


                    } catch (error) {

                        console.error(
                            'AJAX validation error:',
                            error
                        );

                    }

                }


                function showError(
                    field,
                    message
                ) {

                    let input = null;

                    let errorElement = null;


                    if (field === 'name') {

                        input = nameInput;

                        errorElement =
                            document.getElementById(
                                'name-error'
                            );

                    }

                    if (field === 'email') {

                        input = emailInput;

                        errorElement =
                            document.getElementById(
                                'email-error'
                            );

                    }

                    if (field === 'password') {

                        input = passwordInput;

                        errorElement =
                            document.getElementById(
                                'password-error'
                            );

                    }

                    if (
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


                function clearErrors() {

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
                                    'is-invalid'
                                );

                            }
                        );

                }


                function updateStatuses(
                    errors
                ) {

                    setStatus(
                        'status-name',
                        errors.name ?
                        'invalid' :
                        nameInput.value.length >= 2 ?
                        'valid' :
                        'pending',
                        'Name'
                    );


                    setStatus(
                        'status-email',
                        errors.email ?
                        'invalid' :
                        emailInput.value ?
                        'valid' :
                        'pending',
                        'Email'
                    );


                    if (!passwordInput.value) {

                        setStatus(
                            'status-password',
                            'pending',
                            'New password'
                        );

                    } else {

                        setStatus(
                            'status-password',
                            errors.password ?
                            'invalid' :
                            'valid',
                            'New password'
                        );

                    }


                    if (
                        !confirmInput.value
                    ) {

                        setStatus(
                            'status-confirm',
                            'pending',
                            'Password confirmation'
                        );

                    } else if (
                        confirmInput.value ===
                        passwordInput.value
                    ) {

                        setStatus(
                            'status-confirm',
                            'valid',
                            'Password confirmation'
                        );

                    }

                }


                function setStatus(
                    id,
                    status,
                    text
                ) {

                    const element =
                        document.getElementById(id);


                    element.classList.remove(
                        'valid',
                        'invalid'
                    );


                    const icon =
                        element.querySelector(
                            'i'
                        );


                    if (status === 'valid') {

                        element.classList.add(
                            'valid'
                        );

                        icon.className =
                            'fa fa-circle-check';

                    } else if (
                        status === 'invalid'
                    ) {

                        element.classList.add(
                            'invalid'
                        );

                        icon.className =
                            'fa fa-circle-xmark';

                    } else {

                        icon.className =
                            'fa fa-circle';

                    }


                    element.lastChild.textContent =
                        ' ' + text;

                }


                /*
                |--------------------------------------------------------------------------
                | Input listeners
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


                        checkConfirmation();


                        await sendValidationRequest();


                        if (
                            document.querySelector(
                                '.form-control.is-invalid'
                            )
                        ) {

                            submitButton.disabled =
                                false;

                            submitButton.innerHTML =
                                '<i class="fa fa-save me-1"></i> Update User';

                            return;

                        }


                        submitButton.innerHTML =
                            '<i class="fa fa-spinner fa-spin me-1"></i> Updating...';


                        form.submit();

                    }
                );


                resetPasswordChecklist();

            }
        );
    </script>

</body>

</html>