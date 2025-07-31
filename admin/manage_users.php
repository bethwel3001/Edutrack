<?php
require_once '../includes/config.php';
require_once '../includes/admin_functions.php';

if (!isAdmin()) {
    header("Location: ../login.php");
    exit();
}

$type = $_GET['type'] ?? 'student';
$table = ($type === 'student') ? 'students' : 'staff';

// Handle delete
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $pdo->prepare("DELETE FROM $table WHERE id = ?")->execute([$id]);
    $_SESSION['success'] = "User deleted successfully!";
    header("Location: manage_users.php?type=$type");
    exit();
}

// Fetch users
$users = $pdo->query("SELECT * FROM $table ORDER BY name")->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users | Attendance System</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <header>
        <h1>Manage <?= ucfirst($type) ?>s</h1>
        <nav>
            <a href="dashboard.php">← Back to Admin</a>
        </nav>
    </header>

    <main class="user-management">
        <div class="user-actions">
            <a href="?type=student" class="<?= $type === 'student' ? 'active' : '' ?>">Students</a>
            <a href="?type=staff" class="<?= $type === 'staff' ? 'active' : '' ?>">Staff</a>
        </div>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                <tr>
                    <td><?= $user['id'] ?></td>
                    <td><?= htmlspecialchars($user['name']) ?></td>
                    <td><?= htmlspecialchars($user['email']) ?></td>
                    <td>
                        <a href="edit_user.php?type=<?= $type ?>&id=<?= $user['id'] ?>" class="btn">Edit</a>
                        <a href="?type=<?= $type ?>&delete=<?= $user['id'] ?>" class="btn delete-user">Delete</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </main>
    <script src="../assets/js/admin.js"></script>
</body>
</html>