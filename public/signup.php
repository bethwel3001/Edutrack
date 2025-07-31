<?php
include '../includes/config.php';

// Redirect if already logged in
if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit();
}

// Display errors
if (isset($_SESSION['error'])) {
    $error = $_SESSION['error'];
    unset($_SESSION['error']);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up | EduTrack</title>
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

        /* Signup Container */
        .signup-container {
            max-width: 480px;
            width: 100%;
            margin: 0 auto;
            background: white;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            padding: 2.5rem;
            position: relative;
            overflow: hidden;
        }

        .signup-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 6px;
            background: linear-gradient(90deg, var(--primary), var(--secondary));
        }

        /* Header */
        .signup-header {
            text-align: center;
            margin-bottom: 1.5rem;
        }

        .signup-header h2 {
            color: var(--primary);
            font-size: 1.8rem;
            margin-bottom: 0.5rem;
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

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Form Styles */
        .signup-form {
            display: flex;
            flex-direction: column;
            gap: 1.2rem;
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

        .form-group input,
        .form-group select {
            padding: 0.8rem 1rem;
            border: 1px solid #ddd;
            border-radius: var(--border-radius);
            font-size: 1rem;
            transition: var(--transition);
        }

        .form-group input:focus,
        .form-group select:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.2);
        }

        /* Password Strength Indicator */
        .password-strength {
            height: 4px;
            background: #eee;
            border-radius: 2px;
            margin-top: 0.3rem;
            overflow: hidden;
        }

        .password-strength-bar {
            height: 100%;
            width: 0%;
            background: var(--error);
            transition: var(--transition);
        }

        /* Button Styles */
        .signup-button {
            background-color: var(--primary);
            color: white;
            border: none;
            padding: 0.9rem;
            border-radius: var(--border-radius);
            font-size: 1rem;
            font-weight: 500;
            cursor: pointer;
            transition: var(--transition);
            margin-top: 0.8rem;
        }

        .signup-button:hover {
            background-color: var(--primary-dark);
            transform: translateY(-2px);
        }

        .signup-button:active {
            transform: translateY(0);
        }

        /* Footer Links */
        .signup-footer {
            text-align: center;
            margin-top: 1.5rem;
            font-size: 0.9rem;
            color: var(--gray);
        }

        .signup-footer a {
            color: var(--primary);
            text-decoration: none;
            font-weight: 500;
            transition: var(--transition);
        }

        .signup-footer a:hover {
            text-decoration: underline;
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

        /* Responsive Design */
        @media (max-width: 480px) {
            .signup-container {
                padding: 1.8rem;
            }
            
            .signup-header h2 {
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="signup-container">
        <div class="signup-header">
            <h2>Create Your Account</h2>
        </div>

        <?php if (isset($error)): ?>
            <div class="alert error">
                <?= $error ?>
            </div>
        <?php endif; ?>
        
        <form class="signup-form" action="../includes/auth.php" method="POST">
            <input type="hidden" name="action" value="signup">
            
            <div class="form-group">
                <label for="name">Full Name</label>
                <input type="text" id="name" name="name" required placeholder="Enter your full name">
            </div>
            
            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" required placeholder="Enter your email">
            </div>
            
            <div class="form-group">
                <label for="password">Password</label>
                <div class="password-wrapper">
                    <input type="password" id="password" name="password" required 
                           placeholder="Create a password" oninput="checkPasswordStrength()">
                    <span class="toggle-password" onclick="togglePasswordVisibility()">👁️</span>
                </div>
                <div class="password-strength">
                    <div class="password-strength-bar" id="password-strength-bar"></div>
                </div>
            </div>
            
            <div class="form-group">
                <label for="user_type">I am a:</label>
                <select id="user_type" name="user_type" required onchange="updateIdFieldLabel()">
                    <option value="" disabled selected>Select your role</option>
                    <option value="student">Student</option>
                    <option value="staff">Staff</option>
                </select>
            </div>
            
            <div class="form-group" id="id-field">
                <label id="id-label" for="id_number">Student ID</label>
                <input type="text" id="id_number" name="id_number" required 
                       placeholder="Enter your student ID">
            </div>
            
            <button type="submit" class="signup-button">Create Account</button>
        </form>
        
        <div class="signup-footer">
            <p>Already have an account? <a href="login.php">Log in</a></p>
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

        // Update ID field label based on user type
        function updateIdFieldLabel() {
            const userType = document.getElementById('user_type').value;
            const idLabel = document.getElementById('id-label');
            
            if (userType === 'staff') {
                idLabel.textContent = 'Staff ID';
                document.getElementById('id_number').placeholder = 'Enter your staff ID';
            } else {
                idLabel.textContent = 'Student ID';
                document.getElementById('id_number').placeholder = 'Enter your student ID';
            }
        }

        // Password strength checker
        function checkPasswordStrength() {
            const password = document.getElementById('password').value;
            const strengthBar = document.getElementById('password-strength-bar');
            let strength = 0;
            
            // Check length
            if (password.length >= 8) strength += 1;
            if (password.length >= 12) strength += 1;
            
            // Check for mixed case
            if (/[a-z]/.test(password) && /[A-Z]/.test(password)) strength += 1;
            
            // Check for numbers
            if (/\d/.test(password)) strength += 1;
            
            // Check for special chars
            if (/[^a-zA-Z0-9]/.test(password)) strength += 1;
            
            // Update strength bar
            switch(strength) {
                case 0:
                case 1:
                    strengthBar.style.width = '20%';
                    strengthBar.style.backgroundColor = 'var(--error)';
                    break;
                case 2:
                    strengthBar.style.width = '40%';
                    strengthBar.style.backgroundColor = '#ff9f1c';
                    break;
                case 3:
                    strengthBar.style.width = '70%';
                    strengthBar.style.backgroundColor = '#ffbf69';
                    break;
                case 4:
                case 5:
                    strengthBar.style.width = '100%';
                    strengthBar.style.backgroundColor = 'var(--success)';
                    break;
            }
        }

        // Form validation
        document.querySelector('.signup-form').addEventListener('submit', function(e) {
            const password = document.getElementById('password').value;
            
            // Basic password strength check
            if (password.length < 8) {
                e.preventDefault();
                alert('Password must be at least 8 characters long');
                return false;
            }
            
            return true;
        });

        // Initialize form
        document.addEventListener('DOMContentLoaded', function() {
            updateIdFieldLabel();
        });
    </script>
</body>
</html>