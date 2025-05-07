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

// Query to get sales data based on date range
$query = "SELECT sales.id, sales.total, sales.sale_date, users.username 
          FROM sales 
          JOIN users ON sales.user_id = users.id
          WHERE sales.sale_date BETWEEN ? AND ? 
          ORDER BY sales.sale_date DESC";

$stmt = $conn->prepare($query);
$stmt->bind_param("ss", $startDate, $endDate);
$stmt->execute();
$sales = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Sales History - Inventory System</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    .table th, .table td {
      text-align: center;
    }
  </style>
</head>
<body>

<div class="container mt-5">
  <h3 class="text-primary"><i class="bi bi-bar-chart-fill me-2"></i> Sales History</h3>

  <!-- Date Range Filter -->
  <form method="GET" action="" class="mb-4">
    <div class="row">
      <div class="col-md-4">
        <label for="start_date" class="form-label">Start Date</label>
        <input type="date" class="form-control" name="start_date" value="<?= $startDate ?>" required>
      </div>
      <div class="col-md-4">
        <label for="end_date" class="form-label">End Date</label>
        <input type="date" class="form-control" name="end_date" value="<?= $endDate ?>" required>
      </div>
      <div class="col-md-4 d-flex justify-content-center align-items-end">
        <button type="submit" class="btn btn-primary w-100"><i class="bi bi-search"></i> Filter</button>
      </div>
    </div>
  </form>

  <a href="sales-report.php" class="btn btn-success w-100">Sales Report</a>
  <br><br>
  <!-- Sales Table -->
  <div class="table-responsive">
    <table class="table table-bordered table-hover bg-white">
      <thead class="table-primary">
        <tr>
          <th>#</th>
          <th>Date</th>
          <th>Total Sale Amount</th>
          <th>Salesperson</th>
          <th>Products</th>
        </tr>
      </thead>
      <tbody>
        <?php if ($sales->num_rows > 0): ?>
          <?php while($sale = $sales->fetch_assoc()): ?>
            <tr>
              <td><?= $sale['id'] ?></td>
              <td><?= date('Y-m-d', strtotime($sale['sale_date'])) ?></td>
              <td>Rs. <?= number_format($sale['total'] ?? 0, 2) ?></td>
              <td><?= htmlspecialchars($sale['username']) ?></td>
              <td>
                <a href="sale-details.php?id=<?= $sale['id'] ?>" class="btn btn-info btn-sm">
                  <i class="bi bi-eye"></i> View Products
                </a>
              </td>
            </tr>
          <?php endwhile; ?>
        <?php else: ?>
          <tr>
            <td colspan="5">No sales found in this date range.</td>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>

</div>

</body>
</html>
