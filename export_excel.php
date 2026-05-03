<?php
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}
require_once 'db.php';

// Fetch all submissions
try {
    $stmt = $pdo->query("SELECT * FROM submissions ORDER BY id DESC");
    $submissions = $stmt->fetchAll();
} catch(PDOException $e) {
    die("Error fetching data: " . $e->getMessage());
}

// Set headers to force download as CSV
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=submissions_' . date('Y-m-d') . '.csv');

// Add BOM for UTF-8 to ensure Arabic characters display correctly in Excel
echo "\xEF\xBB\xBF";

// Create a file pointer connected to the output stream
$output = fopen('php://output', 'w');

// Output the column headings
if (count($submissions) > 0) {
    // Get column names from the first row
    $columns = array_keys($submissions[0]);
    fputcsv($output, $columns);
    
    // Output all the rows
    foreach ($submissions as $row) {
        fputcsv($output, $row);
    }
} else {
    fputcsv($output, ['No data available']);
}

fclose($output);
exit;
?>
