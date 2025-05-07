<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

include '../includes/db.php';
require_once('../vendor/tecnickcom/tcpdf/tcpdf.php'); // Include TCPDF

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

// Create PDF instance
$pdf = new TCPDF();
$pdf->AddPage();

// Set title and header
$pdf->SetFont('helvetica', 'B', 16);
$pdf->Cell(0, 10, 'Sales Report', 0, 1, 'C');
$pdf->Ln(5); // Line break

// Add table header
$pdf->SetFont('helvetica', 'B', 12);
$pdf->Cell(30, 10, 'Sale ID', 1, 0, 'C');
$pdf->Cell(40, 10, 'Sale Date', 1, 0, 'C');
$pdf->Cell(60, 10, 'Total Sale Amount', 1, 0, 'C');
$pdf->Cell(60, 10, 'Salesperson', 1, 1, 'C');

// Add sales data
$pdf->SetFont('helvetica', '', 12);
while ($sale = $sales->fetch_assoc()) {
    $pdf->Cell(30, 10, $sale['id'], 1, 0, 'C');
    $pdf->Cell(40, 10, date('Y-m-d', strtotime($sale['sale_date'])), 1, 0, 'C');
    $pdf->Cell(60, 10, 'Rs. ' . number_format($sale['total'], 2), 1, 0, 'C');
    $pdf->Cell(60, 10, $sale['username'], 1, 1, 'C');
}

// Output the PDF
$pdf->Output('sales_report.pdf', 'D'); // 'D' for download
?>
