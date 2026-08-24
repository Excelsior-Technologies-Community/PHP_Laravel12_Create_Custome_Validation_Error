import zxcvbn from 'zxcvbn';
import axios from 'axios';

document.addEventListener('DOMContentLoaded', () => {

    const form = document.getElementById('user-form');

    if (!form) {
        return;
    }

    const validateUrl = form.dataset.validateUrl;

    const nameInput =
        form.querySelector('input[name="name"]');

    const emailInput =
        form.querySelector('input[name="email"]');

    const passwordInput =
        form.querySelector('input[name="password"]');

    const confirmInput =
        form.querySelector(
            'input[name="password_confirmation"]'
        );

    const strengthBar =
        document.getElementById(
            'password-strength-bar'
        );

    const strengthText =
        document.getElementById(
            'password-strength-text'
        );

    const strengthMeter =
        document.getElementById(
            'password-strength-meter'
        );

    const criteriaEl =
        document.getElementById(
            'password-criteria'
        );

    const completeEl =
        document.getElementById(
            'validation-complete'
        );

    const csrfToken =
        document.querySelector(
            'meta[name="csrf-token"]'
        )?.content ||
        document.querySelector(
            'input[name="_token"]'
        )?.value;


    /*
    |--------------------------------------------------------------------------
    | Password strength
    |--------------------------------------------------------------------------
    */

    const strengthLabels = [
        '',
        'Weak',
        'Weak',
        'Fair',
        'Good',
        'Strong'
    ];

    const strengthColors = [
        '#9ca3af',
        '#ef4444',
        '#f97316',
        '#eab308',
        '#4ade80',
        '#22c55e'
    ];


    /*
    |--------------------------------------------------------------------------
    | Debounce
    |--------------------------------------------------------------------------
    */

    const debounce = (fn, delay) => {

        let timer;

        return (...args) => {

            clearTimeout(timer);

            timer = setTimeout(() => {

                fn(...args);

            }, delay);

        };

    };


    /*
    |--------------------------------------------------------------------------
    | Validation status
    |--------------------------------------------------------------------------
    */

    const statusItems =
        form.parentElement.querySelectorAll(
            '[data-status-field]'
        );


    const updateStatus = (
        fieldName,
        status
    ) => {

        statusItems.forEach(item => {

            if (
                item.dataset.statusField !==
                fieldName
            ) {
                return;
            }

            item.classList.remove(
                'valid',
                'invalid',
                'pending'
            );

            const icon =
                item.querySelector('i');

            if (status === 'valid') {

                item.classList.add('valid');

                icon.className =
                    'fa fa-circle-check';

            } else if (status === 'invalid') {

                item.classList.add('invalid');

                icon.className =
                    'fa fa-circle-xmark';

            } else {

                item.classList.add('pending');

                icon.className =
                    'fa fa-circle';

            }

        });

        updateCompletionStatus();

    };


    const updateCompletionStatus = () => {

        if (!completeEl) {
            return;
        }

        const items = [...statusItems];

        const allValid =
            items.length > 0 &&
            items.every(item =>
                item.classList.contains('valid')
            );

        completeEl.style.display =
            allValid
                ? 'block'
                : 'none';

    };


    /*
    |--------------------------------------------------------------------------
    | Error helpers
    |--------------------------------------------------------------------------
    */

    const showError = (
        field,
        message
    ) => {

        const wrapper =
            field.closest('.form-field');

        if (wrapper) {

            wrapper.classList.add(
                'has-error'
            );

            let errorEl =
                wrapper.querySelector(
                    '.error-banner'
                );

            if (!errorEl) {

                errorEl =
                    document.createElement(
                        'div'
                    );

                errorEl.className =
                    'error-banner';

                wrapper.appendChild(
                    errorEl
                );

            }

            errorEl.textContent =
                message;

            errorEl.classList.add(
                'show'
            );
        }

        field.classList.add(
            'is-invalid'
        );

        field.classList.remove(
            'is-valid'
        );

        updateStatus(
            field.name,
            'invalid'
        );

    };


    const clearError = (
        field
    ) => {

        const wrapper =
            field.closest('.form-field');

        if (wrapper) {

            wrapper.classList.remove(
                'has-error'
            );

            const errorEl =
                wrapper.querySelector(
                    '.error-banner'
                );

            if (errorEl) {

                errorEl.classList.remove(
                    'show'
                );

            }

        }

        field.classList.remove(
            'is-invalid'
        );

        field.classList.add(
            'is-valid'
        );

        updateStatus(
            field.name,
            'valid'
        );

    };


    const clearAllErrors = () => {

        form.querySelectorAll(
            '.form-field'
        ).forEach(wrapper => {

            wrapper.classList.remove(
                'has-error'
            );

            const errorEl =
                wrapper.querySelector(
                    '.error-banner'
                );

            if (errorEl) {

                errorEl.classList.remove(
                    'show'
                );

            }

        });

        form.querySelectorAll(
            'input.is-invalid'
        ).forEach(el => {

            el.classList.remove(
                'is-invalid'
            );

        });

    };


    /*
    |--------------------------------------------------------------------------
    | Form data
    |--------------------------------------------------------------------------
    */

    const getFormData = () => ({

        name:
            nameInput
                ? nameInput.value
                : '',

        email:
            emailInput
                ? emailInput.value
                : '',

        password:
            passwordInput
                ? passwordInput.value
                : '',

        password_confirmation:
            confirmInput
                ? confirmInput.value
                : ''

    });


    /*
    |--------------------------------------------------------------------------
    | AJAX validation
    |--------------------------------------------------------------------------
    */

    const validateField = async (
        field
    ) => {

        if (!field) {
            return;
        }

        /*
         * During edit, empty password is valid because
         * it means "keep current password".
         */
        if (
            field.name === 'password' &&
            field.value === ''
        ) {

            clearError(field);

            return;
        }

        try {

            const response =
                await axios.post(
                    validateUrl,
                    getFormData(),
                    {
                        headers: {
                            'X-CSRF-TOKEN':
                                csrfToken,

                            'X-Requested-With':
                                'XMLHttpRequest',

                            'Accept':
                                'application/json'
                        }
                    }
                );

            if (
                response.data.success
            ) {

                clearError(field);

            }

        } catch (error) {

            if (
                error.response &&
                error.response.status === 422
            ) {

                const fieldErrors =
                    error.response.data.errors;

                if (
                    fieldErrors &&
                    fieldErrors[field.name]
                ) {

                    showError(
                        field,
                        fieldErrors[
                            field.name
                        ][0]
                    );

                } else {

                    clearError(field);

                }

            } else {

                updateStatus(
                    field.name,
                    'pending'
                );

            }

        }

    };


    /*
    |--------------------------------------------------------------------------
    | Event listeners
    |--------------------------------------------------------------------------
    */

    const attachValidation =
        (field) => {

            if (!field) {
                return;
            }

            field.addEventListener(
                'blur',
                () => validateField(field)
            );

            field.addEventListener(
                'keyup',
                debounce(
                    () => validateField(field),
                    500
                )
            );

        };


    attachValidation(nameInput);

    attachValidation(emailInput);

    attachValidation(passwordInput);

    attachValidation(confirmInput);


    /*
    |--------------------------------------------------------------------------
    | Password strength meter
    |--------------------------------------------------------------------------
    */

    const updateCriteria = (
        password
    ) => {

        const checks = [

            {
                regex: /.{8,}/,
                text: 'At least 8 characters'
            },

            {
                regex: /[A-Z]/,
                text: 'One uppercase letter'
            },

            {
                regex: /[a-z]/,
                text: 'One lowercase letter'
            },

            {
                regex: /[0-9]/,
                text: 'One number'
            },

            {
                regex: /[\W_]/,
                text: 'One special character'
            }

        ];


        const html =
            checks.map(check => {

                const passed =
                    password &&
                    check.regex.test(password);

                const cls =
                    passed
                        ? 'text-success'
                        : 'text-muted';

                const icon =
                    passed
                        ? 'fa-check-circle'
                        : 'fa-circle';

                return `
                    <span class="${cls}">
                        <i class="fa ${icon} me-1"></i>
                        ${check.text}
                    </span>
                `;

            }).join('<br>');


        if (criteriaEl) {

            criteriaEl.innerHTML =
                html;

        }

    };


    if (
        strengthMeter &&
        passwordInput
    ) {

        const updateStrength = (
            password
        ) => {

            /*
             * Edit form allows an empty password.
             */
            if (!password) {

                strengthMeter.style.display =
                    'none';

                if (strengthBar) {

                    strengthBar.style.width =
                        '0%';

                }

                if (strengthText) {

                    strengthText.textContent =
                        '';

                }

                if (criteriaEl) {

                    criteriaEl.innerHTML =
                        '';

                }

                return;

            }


            strengthMeter.style.display =
                'block';


            const result =
                zxcvbn(password);


            const score =
                result.score;


            const index =
                score + 1;


            if (strengthBar) {

                const percent =
                    ((score + 1) / 5) * 100;

                strengthBar.style.width =
                    percent + '%';

                strengthBar.style.backgroundColor =
                    strengthColors[index];

            }


            if (strengthText) {

                strengthText.textContent =
                    strengthLabels[index];

                strengthText.style.color =
                    strengthColors[index];

            }


            updateCriteria(
                password
            );

        };


        passwordInput.addEventListener(
            'input',
            () => {

                updateStrength(
                    passwordInput.value
                );

                /*
                 * Trigger real-time validation.
                 */
                validateField(
                    passwordInput
                );

            }
        );


        updateStrength(
            passwordInput.value
        );

    }


    /*
    |--------------------------------------------------------------------------
    | Password confirmation
    |--------------------------------------------------------------------------
    */

    if (confirmInput) {

        confirmInput.addEventListener(
            'input',
            debounce(
                () => validateField(
                    confirmInput
                ),
                300
            )
        );

    }


    /*
    |--------------------------------------------------------------------------
    | Submit
    |--------------------------------------------------------------------------
    */

    form.addEventListener(
        'submit',
        () => {

            clearAllErrors();

        }
    );

});