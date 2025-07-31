<?php
require_once '../includes/config.php';
require_once '../includes/attendance_functions.php';

if (!isAdmin()) {
    header("Location: ../login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = $_POST['user_id'];
    $userType = $_POST['user_type'];
    $startDate = $_POST['start_date'];
    $endDate = $_POST['end_date'];
    generateAttendanceReportPDF($userId, $userType, $startDate, $endDate);
    exit();
}

// Fetch all users for dropdown
$students = $pdo->query("SELECT id, name FROM students")->fetchAll();
$staff = $pdo->query("SELECT id, name FROM staff")->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Generate Reports</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <header>
        <h1>Attendance Reports</h1>
        <nav>
            <a href="dashboard.php">← Back to Admin</a>
        </nav>
    </header>

    <main class="report-generator">
        <form method="POST">
            <div class="form-group">
                <label for="user_type">User Type</label>
                <select name="user_type" id="user_type" required>
                    <option value="student">Student</option>
                    <option value="staff">Staff</option>
                </select>
            </div>

            <div class="form-group">
                <label for="user_id">Select User</label>
                <select name="user_id" id="user_id" required>
                    <option value="">-- Select --</option>
                    <optgroup label="Students">
                        <?php foreach ($students as $student): ?>
                        <option value="<?= $student['id'] ?>"><?= htmlspecialchars($student['name']) ?></option>
                        <?php endforeach; ?>
                    </optgroup>
                    <optgroup label="Staff">
                        <?php foreach ($staff as $staffMember): ?>
                        <option value="<?= $staffMember['id'] ?>"><?= htmlspecialchars($staffMember['name']) ?></option>
                        <?php endforeach; ?>
                    </optgroup>
                </select>
            </div>

            <div class="form-group">
                <label for="start_date">Start Date</label>
                <input type="date" name="start_date" required>
            </div>

            <div class="form-group">
                <label for="end_date">End Date</label>
                <input type="date" name="end_date" required>
            </div>

            <button type="submit" class="btn">Generate PDF Report</button>
        </form>
    </main>
    <script src="../assets/js/admin.js"></script>
</body>
</html>