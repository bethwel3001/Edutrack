<?php
require_once 'config.php';

// Mark attendance
function markAttendance($userId, $userType, $date, $morning, $afternoon, $evening) {
    global $pdo;
    try {
        $stmt = $pdo->prepare("
            INSERT INTO attendance (user_id, user_type, date, morning_status, afternoon_status, evening_status)
            VALUES (?, ?, ?, ?, ?, ?)
            ON DUPLICATE KEY UPDATE
            morning_status = VALUES(morning_status),
            afternoon_status = VALUES(afternoon_status),
            evening_status = VALUES(evening_status)
        ");
        $stmt->execute([$userId, $userType, $date, $morning, $afternoon, $evening]);
        return true;
    } catch (PDOException $e) {
        error_log("Attendance error: " . $e->getMessage());
        return false;
    }
}

// Generate PDF report (using TCPDF)
function generateAttendanceReportPDF($userId, $userType, $startDate, $endDate) {
    global $pdo;
    require_once('tcpdf/tcpdf.php');

    $pdf = new TCPDF();
    $pdf->AddPage();
    $pdf->SetFont('helvetica', 'B', 16);
    $pdf->Cell(0, 10, 'Attendance Report', 0, 1, 'C');

    // Fetch data
    $stmt = $pdo->prepare("
        SELECT date, morning_status, afternoon_status, evening_status 
        FROM attendance 
        WHERE user_id = ? AND user_type = ? 
        AND date BETWEEN ? AND ?
        ORDER BY date DESC
    ");
    $stmt->execute([$userId, $userType, $startDate, $endDate]);
    $records = $stmt->fetchAll();

    // Generate table
    $html = '<table border="1">
        <tr>
            <th>Date</th>
            <th>Morning</th>
            <th>Afternoon</th>
            <th>Evening</th>
        </tr>';
    foreach ($records as $record) {
        $html .= sprintf(
            '<tr>
                <td>%s</td>
                <td>%s</td>
                <td>%s</td>
                <td>%s</td>
            </tr>',
            $record['date'],
            ucfirst($record['morning_status']),
            ucfirst($record['afternoon_status']),
            ucfirst($record['evening_status'])
        );
    }
    $html .= '</table>';

    $pdf->writeHTML($html, true, false, false, false, '');
    $pdf->Output('attendance_report.pdf', 'D'); // Force download
}
?>