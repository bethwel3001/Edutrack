<?php
require_once '../includes/config.php';

if (isset($_SESSION['user_id'])) {
    header("Location: ".BASE_URL."/dashboard.php");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login | EduTrack</title>
    <style>
        /* Base Styles */
        :root {
            --primary: #4361ee;
            --primary-dark: #3a56d4;
            --secondary: #f72585;
            --light: #f8f9fa;
            --dark: #212529;
            --gray: #6c757d;
            --success: #4bb543;
            --error: #ef233c;
            --border-radius: 8px;
            --box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            --transition: all 0.3s ease;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            line-height: 1.6;
            background-color: #f5f7ff;
            color: var(--dark);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 20px;
        }

        /* Login Container */
        .login-container {
            max-width: 420px;
            width: 100%;
            margin: 0 auto;
            background: white;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            padding: 2.5rem;
            position: relative;
            overflow: hidden;
        }

        .login-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 6px;
            background: linear-gradient(90deg, var(--primary), var(--secondary));
        }

        /* Header */
        .login-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .login-header h2 {
            color: var(--primary);
            font-size: 1.8rem;
            margin-bottom: 0.5rem;
        }

        .login-header p {
            color: var(--gray);
            font-size: 0.95rem;
        }

        /* Alert Messages */
        .alert {
            padding: 0.8rem 1rem;
            margin-bottom: 1.5rem;
            border-radius: var(--border-radius);
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            animation: fadeIn 0.4s ease-out;
        }

        .alert.error {
            background-color: rgba(239, 35, 60, 0.1);
            color: var(--error);
            border-left: 4px solid var(--error);
        }

        .alert.success {
            background-color: rgba(75, 181, 67, 0.1);
            color: var(--success);
            border-left: 4px solid var(--success);
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Form Styles */
        .login-form {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .form-group label {
            font-weight: 500;
            font-size: 0.95rem;
            color: var(--dark);
        }

        .form-group input {
            padding: 0.8rem 1rem;
            border: 1px solid #ddd;
            border-radius: var(--border-radius);
            font-size: 1rem;
            transition: var(--transition);
        }

        .form-group input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.2);
        }

        /* Button Styles */
        .login-button {
            background-color: var(--primary);
            color: white;
            border: none;
            padding: 0.9rem;
            border-radius: var(--border-radius);
            font-size: 1rem;
            font-weight: 500;
            cursor: pointer;
            transition: var(--transition);
            margin-top: 0.5rem;
        }

        .login-button:hover {
            background-color: var(--primary-dark);
            transform: translateY(-2px);
        }

        .login-button:active {
            transform: translateY(0);
        }

        /* Footer Links */
        .login-footer {
            text-align: center;
            margin-top: 1.5rem;
            font-size: 0.9rem;
            color: var(--gray);
        }

        .login-footer a {
            color: var(--primary);
            text-decoration: none;
            font-weight: 500;
            transition: var(--transition);
        }

        .login-footer a:hover {
            text-decoration: underline;
        }

        /* Responsive Design */
        @media (max-width: 480px) {
            .login-container {
                padding: 1.8rem;
            }
            
            .login-header h2 {
                font-size: 1.5rem;
            }
        }

        /* Password Toggle */
        .password-wrapper {
            position: relative;
        }

        .toggle-password {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: var(--gray);
            font-size: 1.1rem;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            <h2>Welcome to EduTrack</h2>
            <p>Sign in to access your dashboard</p>
        </div>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert error">
                <?= $_SESSION['error']; unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>
        
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert success">
                <?= $_SESSION['success']; unset($_SESSION['success']); ?>
            </div>
        <?php endif; ?>
        
        <form class="login-form" action="../includes/auth.php" method="POST">
            <input type="hidden" name="action" value="login">
            
            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" required placeholder="Enter your email">
            </div>
            
            <div class="form-group">
                <label for="password">Password</label>
                <div class="password-wrapper">
                    <input type="password" id="password" name="password" required placeholder="Enter your password">
                    <span class="toggle-password" onclick="togglePasswordVisibility()">👁️</span>
                </div>
            </div>
            
            <button type="submit" class="login-button">Login</button>
        </form>
        
        <div class="login-footer">
            <p>Don't have an account? <a href="signup.php">Sign Up</a></p>
        </div>
    </div>

    <script>
        // Toggle password visibility
        function togglePasswordVisibility() {
            const passwordInput = document.getElementById('password');
            const toggleIcon = document.querySelector('.toggle-password');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.textContent = '👁️';
            } else {
                passwordInput.type = 'password';
                toggleIcon.textContent = '👁️';
            }
        }

        // Form validation
        document.querySelector('.login-form').addEventListener('submit', function(e) {
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;
            
            // Basic validation
            if (!email || !password) {
                e.preventDefault();
                alert('Please fill in all fields');
                return false;
            }
            
            // Email format validation
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email)) {
                e.preventDefault();
                alert('Please enter a valid email address');
                return false;
            }
            
            return true;
        });

        // Add focus styles dynamically
        const inputs = document.querySelectorAll('input');
        inputs.forEach(input => {
            input.addEventListener('focus', function() {
                this.style.borderColor = '#4361ee';
                this.style.boxShadow = '0 0 0 3px rgba(67, 97, 238, 0.2)';
            });
            
            input.addEventListener('blur', function() {
                this.style.borderColor = '#ddd';
                this.style.boxShadow = 'none';
            });
        });
    </script>
</body>
</html>