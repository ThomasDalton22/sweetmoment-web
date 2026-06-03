<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Auth - SweetMoments</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .auth-container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            width: 100%;
            max-width: 450px;
            position: relative;
        }

        .auth-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
            position: relative;
        }

        .auth-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 20"><defs><radialGradient id="a" cx="50%" cy="40%"><stop offset="0%" stop-color="%23ffffff" stop-opacity="0.1"/><stop offset="100%" stop-color="%23000000" stop-opacity="0.1"/></radialGradient></defs><rect width="100%" height="100%" fill="url(%23a)"/></svg>');
            opacity: 0.1;
        }

        .auth-header h1 {
            font-size: 2rem;
            font-weight: 600;
            margin-bottom: 10px;
            position: relative;
            z-index: 1;
        }

        .auth-header p {
            opacity: 0.9;
            position: relative;
            z-index: 1;
        }

        .auth-tabs {
            display: flex;
            background: #f8f9fa;
            margin: 0;
            border-bottom: 1px solid #e9ecef;
        }

        .auth-tab {
            flex: 1;
            padding: 15px 20px;
            text-align: center;
            background: none;
            border: none;
            cursor: pointer;
            font-weight: 500;
            color: #6c757d;
            transition: all 0.3s ease;
            position: relative;
        }

        .auth-tab.active {
            color: #667eea;
            background: white;
        }

        .auth-tab.active::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .auth-content {
            padding: 30px;
        }

        .auth-form {
            display: none;
        }

        .auth-form.active {
            display: block;
            animation: slideIn 0.3s ease;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .form-group {
            margin-bottom: 20px;
            position: relative;
        }

        .form-label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: #495057;
            font-size: 0.9rem;
        }

        .form-input {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e9ecef;
            border-radius: 10px;
            font-size: 14px;
            transition: all 0.3s ease;
            background: #f8f9fa;
        }

        .form-input:focus {
            outline: none;
            border-color: #667eea;
            background: white;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .form-input.error {
            border-color: #dc3545;
            background: #fff5f5;
        }

        .input-icon {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #6c757d;
            margin-top: 12px;
        }

        .error-message {
            color: #dc3545;
            font-size: 0.8rem;
            margin-top: 5px;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .btn-auth {
            width: 100%;
            padding: 12px;
            border: none;
            border-radius: 10px;
            font-weight: 600;
            font-size: 16px;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
        }

        .btn-secondary {
            background: #6c757d;
            color: white;
        }

        .btn-google {
            background: white;
            color: #333;
            border: 2px solid #e9ecef;
            margin-top: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .btn-google:hover {
            background: #f8f9fa;
            border-color: #667eea;
        }

        .divider {
            text-align: center;
            margin: 20px 0;
            position: relative;
            color: #6c757d;
            font-size: 0.9rem;
        }

        .divider::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            height: 1px;
            background: #e9ecef;
            z-index: 1;
        }

        .divider span {
            background: white;
            padding: 0 15px;
            position: relative;
            z-index: 2;
        }

        .auth-footer {
            text-align: center;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #e9ecef;
        }

        .auth-footer a {
            color: #667eea;
            text-decoration: none;
            font-weight: 500;
        }

        .auth-footer a:hover {
            text-decoration: underline;
        }

        .role-selector {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 10px;
            margin-top: 10px;
        }

        .role-option {
            padding: 10px;
            border: 2px solid #e9ecef;
            border-radius: 8px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            background: #f8f9fa;
        }

        .role-option.selected {
            border-color: #667eea;
            background: rgba(102, 126, 234, 0.1);
            color: #667eea;
        }

        .role-option i {
            display: block;
            font-size: 1.2rem;
            margin-bottom: 5px;
        }

        .gender-selector {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
            margin-top: 10px;
        }

        .gender-option {
            padding: 12px;
            border: 2px solid #e9ecef;
            border-radius: 8px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            background: #f8f9fa;
        }

        .gender-option.selected {
            border-color: #667eea;
            background: rgba(102, 126, 234, 0.1);
            color: #667eea;
        }

        .loading {
            pointer-events: none;
            opacity: 0.7;
        }

        .loading::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 20px;
            height: 20px;
            border: 2px solid transparent;
            border-top: 2px solid white;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            to {
                transform: translate(-50%, -50%) rotate(360deg);
            }
        }

        .success-message {
            background: #d4edda;
            color: #155724;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 20px;
            display: none;
        }

        .alert-error {
            background: #f8d7da;
            color: #721c24;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 20px;
            border: 1px solid #f5c6cb;
        }

        @media (max-width: 576px) {
            .auth-container {
                margin: 10px;
                border-radius: 15px;
            }

            .auth-header {
                padding: 20px;
            }

            .auth-header h1 {
                font-size: 1.5rem;
            }

            .auth-content {
                padding: 20px;
            }

            .role-selector {
                grid-template-columns: 1fr;
                gap: 8px;
            }
        }
    </style>
</head>

<body>
    <div class="auth-container">
        <!-- Header -->
        <div class="auth-header">
            <h1><i class="fas fa-heart me-2"></i>SweetMoments</h1>
            <p>Your perfect moment starts here</p>
        </div>

        <!-- Tabs -->
        <div class="auth-tabs">
            <button class="auth-tab active" onclick="switchTab('login')">
                <i class="fas fa-sign-in-alt me-2"></i>Login
            </button>
            <button class="auth-tab" onclick="switchTab('register')">
                <i class="fas fa-user-plus me-2"></i>Register
            </button>
        </div>

        <!-- Content -->
        <div class="auth-content">
            <!-- Login Form -->
            <div id="loginForm" class="auth-form active">
                <form action="{{ route('login') }}" method="POST">
                    @csrf

                    <!-- Login Errors -->
                    @if ($errors->has('login_error') || $errors->has('username') || $errors->has('password'))
                        <div class="alert-error">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            {{ $errors->first('login_error') ?: $errors->first('username') ?: $errors->first('password') }}
                        </div>
                    @endif

                    <div class="form-group">
                        <label for="login_username" class="form-label">
                            <i class="fas fa-user me-2"></i>Username
                        </label>
                        <input type="text" id="login_username" name="username" class="form-input"
                            value="{{ old('username') }}" required>
                        <i class="fas fa-user input-icon"></i>
                    </div>

                    <div class="form-group">
                        <label for="login_password" class="form-label">
                            <i class="fas fa-lock me-2"></i>Password
                        </label>
                        <input type="password" id="login_password" name="password" class="form-input" required>
                        <i class="fas fa-eye input-icon toggle-password" onclick="togglePassword('login_password')"></i>
                    </div>

                    <button type="submit" class="btn-auth btn-primary">
                        <i class="fas fa-sign-in-alt me-2"></i>Login
                    </button>

                    {{-- Uncomment if you want Google login
                    <div class="divider">
                        <span>or</span>
                    </div>

                    <a href="{{ route('redirect') }}" class="btn-auth btn-google">
                        <img src="https://developers.google.com/identity/images/g-logo.png" alt="Google" width="20">
                        Continue with Google
                    </a>
                    --}}
                </form>

                <div class="auth-footer">
                    <a href="{{ route('home') }}">
                        <i class="fas fa-home me-2"></i>Back to Home
                    </a>
                </div>
            </div>

            <!-- Register Form -->
            <div id="registerForm" class="auth-form">
                <div id="success-message" class="success-message">
                    <i class="fas fa-check-circle me-2"></i>
                    Registration successful! Please login.
                </div>

                <div id="error-alert" class="alert-error" style="display: none;">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    <span id="error-text"></span>
                </div>

                <form id="register-form" action="{{ route('register') }}" method="POST">
                    @csrf

                    <div class="form-group">
                        <label for="name" class="form-label">
                            <i class="fas fa-id-card me-2"></i>Full Name
                        </label>
                        <input type="text" id="name" name="name" class="form-input"
                            value="{{ old('name') }}" required>
                        <i class="fas fa-id-card input-icon"></i>
                        <div class="error-message" id="name_error"></div>
                    </div>

                    <div class="form-group">
                        <label for="email" class="form-label">
                            <i class="fas fa-envelope me-2"></i>Email Address
                        </label>
                        <input type="email" id="email" name="email" class="form-input"
                            value="{{ old('email') }}" required>
                        <i class="fas fa-envelope input-icon"></i>
                        <div class="error-message" id="email_error"></div>
                    </div>

                    <div class="form-group">
                        <label for="username" class="form-label">
                            <i class="fas fa-user me-2"></i>Username
                        </label>
                        <input type="text" id="username" name="username" class="form-input"
                            value="{{ old('username') }}" required>
                        <i class="fas fa-user input-icon"></i>
                        <div class="error-message" id="username_error"></div>
                    </div>

                    <div class="form-group">
                        <label for="password" class="form-label">
                            <i class="fas fa-lock me-2"></i>Password
                        </label>
                        <input type="password" id="password" name="password" class="form-input" required>
                        <i class="fas fa-eye input-icon toggle-password" onclick="togglePassword('password')"></i>
                        <div class="error-message" id="password_error"></div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-venus-mars me-2"></i>Gender
                        </label>
                        <div class="gender-selector">
                            <div class="gender-option" onclick="selectGender('Laki-Laki')" data-value="Laki-Laki">
                                <i class="fas fa-mars"></i>
                                <div>Male</div>
                            </div>
                            <div class="gender-option" onclick="selectGender('Perempuan')" data-value="Perempuan">
                                <i class="fas fa-venus"></i>
                                <div>Female</div>
                            </div>
                        </div>
                        <input type="hidden" id="gender" name="gender"
                            value="{{ old('gender', 'Laki-Laki') }}">
                        <div class="error-message" id="gender_error"></div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-user-tag me-2"></i>Role
                        </label>
                        
                        <div class="role-selector">
                            <div class="role-option selected" onclick="selectRole('user')" data-value="user">
                                <i class="fas fa-user"></i>
                                <div>User</div>
                            </div>
                            <div class="role-option" onclick="selectRole('vendor')" data-value="vendor">
                                <i class="fas fa-store"></i>
                                <div>Vendor</div>
                            </div>
                            {{-- <div class="role-option" onclick="selectRole('admin')" data-value="admin">
                                <i class="fas fa-user-shield"></i>
                                <div>Admin</div>
                            </div> --}}
                        </div>
                        <input type="hidden" id="role" name="role" value="{{ old('role', 'user') }}">
                        <div class="error-message" id="role_error"></div>
                    </div>

                    <div class="form-group">
                        <label for="address" class="form-label">
                            <i class="fas fa-map-marker-alt me-2"></i>Address
                        </label>
                        <input type="text" id="address" name="address" class="form-input"
                            value="{{ old('address') }}" required>
                        <i class="fas fa-map-marker-alt input-icon"></i>
                        <div class="error-message" id="address_error"></div>
                    </div>

                    <button type="submit" class="btn-auth btn-primary" id="register-btn">
                        <i class="fas fa-user-plus me-2"></i>Register
                    </button>
                </form>

                <div class="auth-footer">
                    <span>Already have an account? </span>
                    <a href="#" onclick="switchTab('login')">Login now</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Tab switching
        function switchTab(tab) {
            // Update tab buttons
            document.querySelectorAll('.auth-tab').forEach(t => t.classList.remove('active'));
            event.target.classList.add('active');

            // Update forms
            document.querySelectorAll('.auth-form').forEach(f => f.classList.remove('active'));

            if (tab === 'login') {
                document.getElementById('loginForm').classList.add('active');
            } else {
                document.getElementById('registerForm').classList.add('active');
            }

            // Clear any error messages
            clearErrors();
        }

        // Role selection
        function selectRole(role) {
            document.querySelectorAll('.role-option').forEach(r => r.classList.remove('selected'));
            event.target.closest('.role-option').classList.add('selected');
            document.getElementById('role').value = role;
        }

        // Gender selection
        function selectGender(gender) {
            document.querySelectorAll('.gender-option').forEach(g => g.classList.remove('selected'));
            event.target.closest('.gender-option').classList.add('selected');
            document.getElementById('gender').value = gender;
        }

        // Password toggle
        function togglePassword(inputId) {
            const input = document.getElementById(inputId);
            const icon = event.target;

            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }

        // Clear all error messages
        function clearErrors() {
            document.querySelectorAll('.error-message').forEach(e => e.innerHTML = '');
            document.querySelectorAll('.form-input').forEach(i => i.classList.remove('error'));
            document.getElementById('error-alert').style.display = 'none';
        }

        // Display errors
        function displayErrors(errors) {
            clearErrors();

            let hasErrors = false;
            for (const field in errors) {
                hasErrors = true;
                const errorElement = document.getElementById(`${field}_error`);
                const inputElement = document.getElementById(field);

                if (errorElement && inputElement) {
                    errorElement.innerHTML = `<i class="fas fa-exclamation-circle"></i> ${errors[field][0]}`;
                    inputElement.classList.add('error');
                }
            }

            if (hasErrors) {
                document.getElementById('error-alert').style.display = 'block';
                document.getElementById('error-text').textContent = 'Please fix the errors below.';
            }
        }

        // Register form submission
        document.getElementById('register-form').addEventListener('submit', async function(e) {
            e.preventDefault();

            const form = e.target;
            const formData = new FormData(form);
            const submitBtn = document.getElementById('register-btn');

            // Add loading state
            submitBtn.classList.add('loading');
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Registering...';

            try {
                const response = await fetch(form.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                });

                if (response.ok) {
                    // Success
                    document.getElementById('success-message').style.display = 'block';
                    form.reset();

                    // Reset selections
                    document.querySelectorAll('.role-option').forEach(r => r.classList.remove('selected'));
                    document.querySelector('.role-option[data-value="user"]').classList.add('selected');
                    document.getElementById('role').value = 'user';

                    document.querySelectorAll('.gender-option').forEach(g => g.classList.remove('selected'));
                    document.querySelector('.gender-option[data-value="Laki-Laki"]').classList.add('selected');
                    document.getElementById('gender').value = 'Laki-Laki';

                    // Auto switch to login after 2 seconds
                    setTimeout(() => {
                        switchTab('login');
                    }, 2000);

                } else if (response.status === 422) {
                    // Validation errors
                    const data = await response.json();
                    displayErrors(data.errors);
                } else {
                    // Other errors
                    document.getElementById('error-alert').style.display = 'block';
                    document.getElementById('error-text').textContent =
                        'Registration failed. Please try again.';
                }
            } catch (error) {
                console.error('Error:', error);
                document.getElementById('error-alert').style.display = 'block';
                document.getElementById('error-text').textContent =
                    'Network error. Please check your connection.';
            } finally {
                // Remove loading state
                submitBtn.classList.remove('loading');
                submitBtn.innerHTML = '<i class="fas fa-user-plus me-2"></i>Register';
            }
        });

        // Initialize default selections
        document.addEventListener('DOMContentLoaded', function() {
            // Set default gender selection
            const defaultGender = document.getElementById('gender').value || 'Laki-Laki';
            document.querySelector(`.gender-option[data-value="${defaultGender}"]`).classList.add('selected');

            // Set default role selection
            const defaultRole = document.getElementById('role').value || 'user';
            document.querySelector(`.role-option[data-value="${defaultRole}"]`).classList.add('selected');
        });
    </script>
</body>

</html>
