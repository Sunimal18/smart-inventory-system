<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

include '../includes/db.php';

// Default date range
$startDate = isset($_GET['start_date']) ? $_GET['start_date'] : date('Y-m-01');
$endDate = isset($_GET['end_date']) ? $_GET['end_date'] : date('Y-m-d');

// Query to get sales data for the given period
$query = "SELECT sales.id, sales.sale_date, sales.total, users.username 
          FROM sales 
          JOIN users ON sales.user_id = users.id
          WHERE sales.sale_date BETWEEN ? AND ? 
          ORDER BY sales.sale_date DESC";
$stmt = $conn->prepare($query);
$stmt->bind_param("ss", $startDate, $endDate);
$stmt->execute();
$sales = $stmt->get_result();

// Create CSV file output
header('Content-Type: text/csv');
header('Content-Disposition: attachment;filename="sales_report.csv"');

$output = fopen('php://output', 'w');
fputcsv($output, ['Sale ID', 'Sale Date', 'Total Sale Amount', 'Salesperson']);

// Fetch and write each sale record to the CSV
while ($sale = $sales->fetch_assoc()) {
    fputcsv($output, [
        $sale['id'],
        date('Y-m-d', strtotime($sale['sale_date'])),
        number_format($sale['total'], 2),
        $sale['username']
    ]);
}

fclose($output);
exit();
