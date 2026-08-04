import zxcvbn from 'zxcvbn';
import axios from 'axios';

document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('user-form');
    if (!form) return;

    const validateUrl = form.dataset.validateUrl;

    const nameInput      = form.querySelector('input[name="name"]');
    const emailInput     = form.querySelector('input[name="email"]');
    const passwordInput  = form.querySelector('input[name="password"]');
    const confirmInput    = form.querySelector('input[name="password_confirmation"]');
    const strengthBar     = document.getElementById('password-strength-bar');
    const strengthText    = document.getElementById('password-strength-text');
    const strengthMeter   = document.getElementById('password-strength-meter');
    const criteriaEl      = document.getElementById('password-criteria');
    const csrfToken       = document.querySelector('meta[name="csrf-token"]')?.content ||
                            document.querySelector('input[name="_token"]')?.value;

    const strengthLabels = ['', 'Weak', 'Weak', 'Fair', 'Good', 'Strong'];
    const strengthColors = ['#9ca3af', '#ef4444', '#f97316', '#eab308', '#4ade80', '#22c55e'];

    // ---- Helpers ----

    const debounce = (fn, delay) => {
        let timer;
        return (...args) => {
            clearTimeout(timer);
            timer = setTimeout(() => fn.apply(this, args), delay);
        };
    };

    const showError = (field, message) => {
        const wrapper = field.closest('.form-field');
        if (wrapper) {
            wrapper.classList.add('has-error');
            let errorEl = wrapper.querySelector('.error-banner');
            if (!errorEl) {
                errorEl = document.createElement('div');
                errorEl.className = 'error-banner';
                wrapper.appendChild(errorEl);
            }
            errorEl.textContent = message;
            errorEl.classList.add('show');
        }
        field.classList.add('is-invalid');
    };

    const clearError = (field) => {
        const wrapper = field.closest('.form-field');
        if (wrapper) {
            wrapper.classList.remove('has-error');
            const errorEl = wrapper.querySelector('.error-banner');
            if (errorEl) {
                errorEl.classList.remove('show');
            }
        }
        field.classList.remove('is-invalid');
    };

    const clearAllErrors = () => {
        form.querySelectorAll('.form-field').forEach(wrapper => {
            wrapper.classList.remove('has-error');
            const errorEl = wrapper.querySelector('.error-banner');
            if (errorEl) {
                errorEl.classList.remove('show');
            }
        });
        form.querySelectorAll('input.is-invalid').forEach(el => el.classList.remove('is-invalid'));
    };

    // Build a payload of all relevant form fields so rules like "confirmed" work
    const getFormData = () => ({
        name:  nameInput ? nameInput.value : '',
        email: emailInput ? emailInput.value : '',
        password: passwordInput ? passwordInput.value : '',
        password_confirmation: confirmInput ? confirmInput.value : '',
    });

    const validateField = async (field) => {
        const fieldName = field.name;

        try {
            const response = await axios.post(validateUrl, getFormData(), {
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                },
            });

            if (response.data.success) {
                clearError(field);
            }
        } catch (error) {
            if (error.response && error.response.status === 422) {
                const fieldErrors = error.response.data.errors;
                if (fieldErrors[fieldName]) {
                    showError(field, fieldErrors[fieldName][0]);
                } else {
                    clearError(field);
                }
            } else {
                clearError(field);
            }
        }
    };

    // ---- Real-time validation (debounced AJAX) ----

    if (nameInput) {
        nameInput.addEventListener('blur', () => validateField(nameInput));
        nameInput.addEventListener('keyup', debounce(() => validateField(nameInput), 500));
    }

    if (emailInput) {
        emailInput.addEventListener('blur', () => validateField(emailInput));
        emailInput.addEventListener('keyup', debounce(() => validateField(emailInput), 500));
    }

    if (passwordInput) {
        passwordInput.addEventListener('blur', () => validateField(passwordInput));
        passwordInput.addEventListener('keyup', debounce(() => validateField(passwordInput), 500));
    }

    if (confirmInput) {
        confirmInput.addEventListener('blur', () => validateField(confirmInput));
        confirmInput.addEventListener('keyup', debounce(() => validateField(confirmInput), 500));
    }

    // ---- Password Strength Meter (zxcvbn) ----

    const updateCriteria = (password) => {
        const checks = [
            { regex: /.{8,}/, text: 'At least 8 characters' },
            { regex: /[A-Z]/, text: 'One uppercase letter' },
            { regex: /[a-z]/, text: 'One lowercase letter' },
            { regex: /[0-9]/, text: 'One number' },
            { regex: /[\W_]/, text: 'One special character' },
        ];

        const html = checks.map(c => {
            const passed = password && c.regex.test(password);
            const cls = passed ? 'text-success' : 'text-muted';
            const icon = passed ? 'fa-check-circle' : 'fa-circle';
            return `<span class="${cls}"><i class="fa ${icon} me-1"></i>${c.text}</span>`;
        }).join('<br>');

        if (criteriaEl) {
            criteriaEl.innerHTML = html;
        }
    };

    if (strengthMeter && passwordInput) {
        const updateStrength = (password) => {
            const result = zxcvbn(password || '');
            const score = password ? result.score : 0;
            const index = score + 1;

            if (strengthBar) {
                const percent = password ? ((score + 1) / 5) * 100 : 0;
                strengthBar.style.width = percent + '%';
                strengthBar.style.backgroundColor = password ? strengthColors[index] : '#e5e7eb';
            }

            if (strengthText) {
                strengthText.textContent = password ? strengthLabels[index] : '';
                strengthText.style.color = password ? strengthColors[index] : '#9ca3af';
            }

            updateCriteria(password);
        };

        passwordInput.addEventListener('input', () => {
            strengthMeter.style.display = 'block';
            updateStrength(passwordInput.value);
            validateField(passwordInput);
        });

        updateStrength('');
    }

    // ---- Form submit: clear dynamic errors before final validation ----

    if (form) {
        form.addEventListener('submit', () => {
            clearAllErrors();
        });
    }
});
