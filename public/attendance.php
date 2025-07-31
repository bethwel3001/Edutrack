<?php
include '../includes/config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
$userType = $_SESSION['user_type'];
$userId = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $date = $_POST['date'];
        $today = date('Y-m-d');
        
        // Validate date is not in the future
        if ($date > $today) {
            $_SESSION['toast'] = [
                'type' => 'error',
                'message' => 'Cannot mark attendance for future dates'
            ];
            header("Location: attendance.php");
            exit();
        }

        $morning = $_POST['morning_status'];
        $afternoon = $_POST['afternoon_status'];
        $evening = $_POST['evening_status'];

        $stmt = $pdo->prepare("
            INSERT INTO attendance (user_id, user_type, date, morning_status, afternoon_status, evening_status)
            VALUES (?, ?, ?, ?, ?, ?)
            ON DUPLICATE KEY UPDATE
            morning_status = VALUES(morning_status),
            afternoon_status = VALUES(afternoon_status),
            evening_status = VALUES(evening_status)
        ");
        
        $stmt->execute([$userId, $userType, $date, $morning, $afternoon, $evening]);

        $_SESSION['toast'] = [
            'type' => 'success',
            'message' => 'Attendance marked successfully!'
        ];
        header("Location: dashboard.php");
        exit();
    } catch (PDOException $e) {
        error_log("Attendance submission error: " . $e->getMessage());
        $_SESSION['toast'] = [
            'type' => 'error',
            'message' => 'Failed to save attendance. Please try again.'
        ];
        header("Location: attendance.php");
        exit();
    }
}

$today = date('Y-m-d');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mark Attendance | EduTrack</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        /* Toast Notification Styles */
        .toast {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 15px 25px;
            border-radius: 8px;
            color: white;
            font-weight: 500;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            z-index: 1000;
            transform: translateX(200%);
            transition: transform 0.3s ease-out;
        }
        
        .toast.show {
            transform: translateX(0);
        }
        
        .toast.success {
            background-color: #4BB543;
        }
        
        .toast.error {
            background-color: #FF3333;
        }
        
        /* Attendance Form Styles */
        .attendance-form {
            max-width: 600px;
            margin: 2rem auto;
            padding: 2rem;
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        }
        
        .form-group {
            margin-bottom: 1.5rem;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: #333;
        }
        
        .form-group input[type="date"] {
            width: 100%;
            padding: 0.8rem;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 1rem;
        }
        
        .attendance-session {
            margin-bottom: 1.5rem;
            padding: 1rem;
            background: #f8f9fa;
            border-radius: 8px;
        }
        
        .attendance-session h3 {
            margin-bottom: 0.8rem;
            color: #4361ee;
        }
        
        .radio-group {
            display: flex;
            gap: 1.5rem;
        }
        
        .radio-group label {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            cursor: pointer;
        }
        
        /* Button Styles */
        .btn {
            display: inline-block;
            padding: 0.8rem 1.8rem;
            border-radius: 50px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
            font-size: 1rem;
            border: none;
            cursor: pointer;
        }
        
        .btn-primary {
            background-color: #4361ee;
            color: white;
        }
        
        .btn-primary:hover {
            background-color: #3a56d4;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(67, 97, 238, 0.3);
        }
        
        .btn-secondary {
            background-color: white;
            color: #4361ee;
            border: 2px solid #4361ee;
        }
        
        .btn-secondary:hover {
            background-color: #f0f2ff;
        }
        
        .button-group {
            display: flex;
            gap: 1rem;
            margin-top: 2rem;
        }
        
        /* Footer Styles */
        footer {
            text-align: center;
            padding: 1.5rem;
            margin-top: 2rem;
            background: #f8f9fa;
            border-top: 1px solid #eee;
        }
        
        footer a {
            color: #4361ee;
            text-decoration: none;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        footer a:hover {
            text-decoration: underline;
        }
        
        /* Responsive Design */
        @media (max-width: 768px) {
            .attendance-form {
                padding: 1.5rem;
                margin: 1rem;
            }
            
            .radio-group {
                flex-direction: column;
                gap: 0.8rem;
            }
            
            .button-group {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <header>
        <h1>Mark Your Attendance</h1>
    </header>
    
    <main class="attendance-form">
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert error"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
        <?php endif; ?>
        
        <form method="POST" id="attendanceForm">
            <div class="form-group">
                <label for="date">Date</label>
                <input type="date" name="date" id="attendanceDate" value="<?= $today ?>" required>
            </div>
            
            <div class="attendance-session">
                <h3>🌞 Morning</h3>
                <div class="radio-group">
                    <label>
                        <input type="radio" name="morning_status" value="present" checked> Present
                    </label>
                    <label>
                        <input type="radio" name="morning_status" value="absent"> Absent
                    </label>
                </div>
            </div>
            
            <div class="attendance-session">
                <h3>🌇 Afternoon</h3>
                <div class="radio-group">
                    <label>
                        <input type="radio" name="afternoon_status" value="present" checked> Present
                    </label>
                    <label>
                        <input type="radio" name="afternoon_status" value="absent"> Absent
                    </label>
                </div>
            </div>
            
            <div class="attendance-session">
                <h3>🌃 Evening</h3>
                <div class="radio-group">
                    <label>
                        <input type="radio" name="evening_status" value="present" checked> Present
                    </label>
                    <label>
                        <input type="radio" name="evening_status" value="absent"> Absent
                    </label>
                </div>
            </div>
            
            <div class="button-group">
                <button type="submit" class="btn btn-primary">Submit Attendance</button>
                <a href="dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
            </div>
        </form>
    </main>
    
    <footer>
        <p><a href="dashboard.php">← Back to Dashboard</a></p>
    </footer>
    
    <?php if (isset($_SESSION['toast'])): ?>
        <div class="toast <?= $_SESSION['toast']['type'] ?> show" id="toast">
            <?= $_SESSION['toast']['message'] ?>
        </div>
        <?php unset($_SESSION['toast']); ?>
    <?php endif; ?>
    
    <script>
        // Toast notification
        document.addEventListener('DOMContentLoaded', function() {
            const toast = document.getElementById('toast');
            if (toast) {
                setTimeout(() => {
                    toast.classList.remove('show');
                    setTimeout(() => toast.remove(), 300);
                }, 5000);
            }
            
            // Future date validation
            const form = document.getElementById('attendanceForm');
            const dateInput = document.getElementById('attendanceDate');
            const today = new Date().toISOString().split('T')[0];
            
            dateInput.max = today;
            
            form.addEventListener('submit', function(e) {
                const selectedDate = dateInput.value;
                if (selectedDate > today) {
                    e.preventDefault();
                    showToast('Cannot mark attendance for future dates', 'error');
                }
            });
            
            function showToast(message, type) {
                const toast = document.createElement('div');
                toast.className = `toast ${type} show`;
                toast.textContent = message;
                document.body.appendChild(toast);
                
                setTimeout(() => {
                    toast.classList.remove('show');
                    setTimeout(() => toast.remove(), 300);
                }, 5000);
            }
        });
    </script>
</body>
</html>