<?php
include '../includes/config.php';

// Redirect if not admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'staff') {
    header("Location: ../login.php");
    exit();
}

// Check if user is admin
$stmt = $pdo->prepare("SELECT role FROM staff WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();

if ($user['role'] !== 'admin') {
    header("Location: ../dashboard.php");
    exit();
}

// Get total users
$students = $pdo->query("SELECT COUNT(*) FROM students")->fetchColumn();
$staff = $pdo->query("SELECT COUNT(*) FROM staff")->fetchColumn();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | Attendance System</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <header>
        <h1>Admin Dashboard</h1>
        <nav>
            <a href="manage_users.php">Manage Users</a>
            <a href="reports.php">Reports</a>
            <a href="../dashboard.php">User Dashboard</a>
            <a href="../logout.php">Logout</a>
        </nav>
    </header>

    <main class="admin-dashboard">
        <div class="stats-grid">
            <div class="stat-card">
                <h3>Total Students</h3>
                <p><?= $students ?></p>
            </div>
            <div class="stat-card">
                <h3>Total Staff</h3>
                <p><?= $staff ?></p>
            </div>
        </div>

        <div class="quick-actions">
            <a href="manage_users.php?type=student" class="btn">Manage Students</a>
            <a href="manage_users.php?type=staff" class="btn">Manage Staff</a>
            <a href="reports.php" class="btn">Generate Reports</a>
        </div>
    </main>

    <footer>
        <p>© 2024 Attendance System | Admin Panel</p>
    </footer>
</body>
</html>