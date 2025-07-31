<?php
require_once '../includes/config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ".BASE_URL."/login.php");
    exit();
}

// Get user details
$table = ($_SESSION['user_type'] === 'student') ? 'students' : 'staff';
$stmt = $pdo->prepare("SELECT * FROM $table WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();

// Get attendance records (last 30 days)
$stmt = $pdo->prepare("
    SELECT date, morning_status, afternoon_status, evening_status 
    FROM attendance 
    WHERE user_id = ? AND user_type = ?
    ORDER BY date DESC
    LIMIT 30
");
$stmt->execute([$_SESSION['user_id'], $_SESSION['user_type']]);
$attendanceRecords = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    <style>
        :root {
            --primary: #4361ee;
            --secondary: #3f37c9;
            --accent: #4cc9f0;
            --light: #f8f9fa;
            --dark: #212529;
            --success: #2ecc71;
            --danger: #e74c3c;
            --font-main: 'Segoe UI', system-ui, sans-serif;
            --font-heading: 'Poppins', sans-serif;
        }

        body {
            margin: 0;
            font-family: var(--font-main);
            background: var(--light);
            color: var(--dark);
            padding: 1.5rem;
            max-width: 1200px;
            margin: auto;
        }

        .dashboard-header {
            text-align: center;
            margin-bottom: 2rem;
            animation: slideFade 1s ease-out;
        }

        .dashboard-header h1 {
            font-size: 2rem;
            font-family: var(--font-heading);
            color: var(--primary);
            margin-bottom: 0.5rem;
        }

        .dashboard-header p {
            font-size: 1.1rem;
            color: #555;
        }

        nav {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 1rem;
            margin-bottom: 2rem;
        }

        nav a {
            padding: 0.6rem 1.4rem;
            border-radius: 30px;
            font-size: 0.95rem;
            font-weight: 600;
            text-decoration: none;
            transition: 0.3s ease;
        }

        nav a:first-child {
            background: var(--primary);
            color: white;
        }

        nav a:first-child:hover {
            background: var(--secondary);
        }

        nav a:last-child {
            border: 2px solid var(--danger);
            color: var(--danger);
            background: transparent;
        }

        nav a:last-child:hover {
            background: var(--danger);
            color: white;
        }

        .attendance-report {
            background: white;
            border-radius: 10px;
            padding: 2rem;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06);
            animation: fadeIn 1s ease-out;
        }

        .attendance-report h2 {
            font-size: 1.5rem;
            margin-bottom: 1.2rem;
            color: var(--primary);
        }

        .print-button {
            display: inline-block;
            background: var(--primary);
            color: white;
            padding: 0.6rem 1.5rem;
            border: none;
            border-radius: 30px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-bottom: 1rem;
        }

        .print-button:hover {
            background: var(--secondary);
        }

        .cta-buttons {
            margin-bottom: 1rem;
        }

        .cta-buttons a {
            display: inline-block;
            padding: 0.5rem 1.2rem;
            font-size: 0.9rem;
            color: white;
            background-color: #888;
            border-radius: 30px;
            text-decoration: none;
            transition: 0.3s ease;
        }

        .cta-buttons a:hover {
            background-color: #666;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1rem;
            font-size: 0.95rem;
        }

        th, td {
            padding: 0.8rem;
            border-bottom: 1px solid #ddd;
            text-align: left;
        }

        th {
            background-color: var(--primary);
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f4f4f4;
        }

        .present {
            color: var(--success);
            font-weight: bold;
        }

        .absent {
            color: var(--danger);
            font-weight: bold;
        }

        tr.empty-state td {
            text-align: center;
            color: #888;
        }

        @media (max-width: 768px) {
            body {
                padding: 1rem;
            }

            .dashboard-header h1 {
                font-size: 1.5rem;
            }

            nav {
                flex-direction: column;
                align-items: center;
            }

            table {
                font-size: 0.85rem;
            }

            th, td {
                padding: 0.6rem;
            }

            .print-button, .cta-buttons a {
                font-size: 0.85rem;
                padding: 0.5rem 1rem;
            }
        }

        @media print {
            body {
                background: white;
                font-size: 12pt;
                padding: 0;
            }

            nav, .print-button, .cta-buttons {
                display: none;
            }

            .attendance-report {
                box-shadow: none;
                padding: 0;
            }

            th {
                background-color: var(--primary) !important;
                color: white !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            .dashboard-header::after {
                content: "Name: <?= htmlspecialchars($user['name']) ?> | Role: <?= $_SESSION['user_type'] ?>";
                display: block;
                margin-top: 1rem;
                font-size: 0.95rem;
                color: #555;
            }
        }

        @keyframes fadeIn {
            from {opacity: 0; transform: translateY(20px);}
            to {opacity: 1; transform: translateY(0);}
        }

        @keyframes slideFade {
            0% {opacity: 0; transform: translateY(-30px);}
            100% {opacity: 1; transform: translateY(0);}
        }
    </style>
</head>
<body>
    <div class="dashboard-header">
        <h1>Welcome, <?= htmlspecialchars($user['name']) ?>!</h1>
        <p>You are logged in as a <?= htmlspecialchars($_SESSION['user_type']) ?>.</p>
    </div>

    <nav>
        <a href="attendance.php">Mark Attendance</a>
        <a href="../includes/logout.php">Logout</a>
    </nav>

    <div class="attendance-report">
        <h2>Attendance Report (Last 30 Days)</h2>
        <button class="print-button" onclick="window.print()">Print Report</button>
        <div class="cta-buttons">
            <a href="index.php">Home</a>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Morning</th>
                    <th>Afternoon</th>
                    <th>Evening</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($attendanceRecords as $record): ?>
                <tr>
                    <td><?= date('M j, Y', strtotime($record['date'])) ?></td>
                    <td class="<?= $record['morning_status'] ?>"><?= ucfirst($record['morning_status']) ?></td>
                    <td class="<?= $record['afternoon_status'] ?>"><?= ucfirst($record['afternoon_status']) ?></td>
                    <td class="<?= $record['evening_status'] ?>"><?= ucfirst($record['evening_status']) ?></td>
                </tr>
                <?php endforeach; ?>
                <?php if (empty($attendanceRecords)): ?>
                <tr class="empty-state">
                    <td colspan="4">No attendance records found.</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
