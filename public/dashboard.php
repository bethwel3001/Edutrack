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
  --success: #4bb543;    
  --warning: #f8961e;    
  --danger: #ef233c;   
  --present: #2ecc71;   
  --absent: #e74c3c; 
  
  /* Typography */
  --font-main: 'Segoe UI', system-ui, -apple-system, sans-serif;
  --font-heading: 'Poppins', sans-serif;
}
body {
  font-family: var(--font-main);
  line-height: 1.6;
  color: var(--dark);
  background-color: var(--light);
  min-height: 100vh;
  padding: 2rem;
  max-width: 1200px;
  margin: 0 auto;
}
.dashboard-header {
  text-align: center;
  margin-bottom: 3rem;
  padding-bottom: 1.5rem;
  border-bottom: 1px solid rgba(0, 0, 0, 0.1);
}

.dashboard-header h1 {
  font-family: var(--font-heading);
  font-size: 2.2rem;
  color: var(--primary);
  margin-bottom: 0.5rem;
}

.dashboard-header p {
  font-size: 1.1rem;
  color: var(--dark);
  opacity: 0.8;
}
nav {
  display: flex;
  justify-content: center;
  gap: 1.5rem;
  margin-bottom: 3rem;
}

nav a {
  text-decoration: none;
  padding: 0.7rem 1.5rem;
  border-radius: 50px;
  font-weight: 600;
  transition: all 0.3s ease;
}

nav a:first-child {
  background-color: var(--primary);
  color: white;
  box-shadow: 0 4px 6px rgba(67, 97, 238, 0.2);
}

nav a:first-child:hover {
  background-color: var(--secondary);
  transform: translateY(-2px);
  box-shadow: 0 6px 12px rgba(67, 97, 238, 0.3);
}

nav a:last-child {
  background-color: transparent;
  color: var(--danger);
  border: 2px solid var(--danger);
}

nav a:last-child:hover {
  background-color: var(--danger);
  color: white;
}

/* Attendance Report Section */
.attendance-report {
  background: white;
  border-radius: 12px;
  padding: 2rem;
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
  margin-bottom: 3rem;
}

.attendance-report h2 {
  font-family: var(--font-heading);
  color: var(--primary);
  margin-bottom: 1.5rem;
  font-size: 1.5rem;
}
.print-button {
  background: var(--primary);
  color: white;
  border: none;
  padding: 0.8rem 1.8rem;
  border-radius: 50px;
  font-weight: 600;
  cursor: pointer;
  font-size: 1rem;
  transition: all 0.3s ease;
  margin-bottom: 1.5rem;
  box-shadow: 0 4px 6px rgba(67, 97, 238, 0.2);
}

.print-button:hover {
  background: var(--secondary);
  transform: translateY(-2px);
  box-shadow: 0 6px 12px rgba(67, 97, 238, 0.3);
}
table {
  width: 100%;
  border-collapse: collapse;
  margin: 1.5rem 0;
  font-size: 0.95rem;
}

th {
  background-color: var(--primary);
  color: white;
  padding: 1rem;
  text-align: left;
  font-weight: 600;
}

td {
  padding: 1rem;
  border-bottom: 1px solid rgba(0, 0, 0, 0.05);
}

tr:nth-child(even) {
  background-color: rgba(67, 97, 238, 0.03);
}

tr:hover {
  background-color: rgba(67, 97, 238, 0.05);
}

.present {
  color: var(--present);
  font-weight: 600;
}

.absent {
  color: var(--absent);
  font-weight: 600;
}
tr.empty-state td {
  text-align: center;
  padding: 2rem;
  color: rgba(0, 0, 0, 0.5);
}
@media print {
  body {
    padding: 0;
    font-size: 12pt;
    background: white;
  }
  
  nav, .print-button {
    display: none;
  }
  
  .attendance-report {
    box-shadow: none;
    padding: 0;
  }
  
  table {
    page-break-inside: avoid;
  }
  
  th {
    background-color: var(--primary) !important;
    color: white !important;
    -webkit-print-color-adjust: exact;
    print-color-adjust: exact;
  }
}
@media (max-width: 768px) {
  body {
    padding: 1.5rem;
  }
  
  nav {
    flex-direction: column;
    gap: 1rem;
  }
  
  table {
    font-size: 0.85rem;
  }
  
  th, td {
    padding: 0.75rem;
  }
}

/* Animation */
@keyframes fadeIn {
  from { opacity: 0; transform: translateY(20px); }
  to { opacity: 1; transform: translateY(0); }
}

.attendance-report {
  animation: fadeIn 0.6s ease-out;
}
    </style>
</head>
<body>
    <div class="dashboard-header">
        <h1>Welcome, <?= htmlspecialchars($user['name']) ?>!</h1>
        <p>You are logged in as a <?= $_SESSION['user_type'] ?>.</p>
    </div>
    
    <nav>
        <a href="attendance.php">Mark Attendance</a>
        <a href="../includes/logout.php">Logout</a>
    </nav>
    <div class="attendance-report">
        <h2>Attendance Report (Last 30 Days)</h2>
        <button onclick="window.print()" class="print-button">Print Report</button>
        <!-- To style this button -->
        <div class="cta-buttons"><a href="index.php" class="btn btn-secondary">Home<a></div>
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
                <tr>
                    <td colspan="4">No attendance records found</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <script>
// Enhance print functionality
        document.querySelector('.print-button').addEventListener('click', function() {
// You could also generate a PDF here using JavaScript if needed
            window.print();
        });
    </script>
</body>
</html>