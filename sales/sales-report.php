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

// Get total sales for the given period
$totalSalesQuery = "SELECT SUM(total) as total_sales FROM sales WHERE sale_date BETWEEN ? AND ?";
$stmt = $conn->prepare($totalSalesQuery);
$stmt->bind_param("ss", $startDate, $endDate);
$stmt->execute();
$totalSalesResult = $stmt->get_result()->fetch_assoc();
$totalSales = $totalSalesResult['total_sales'] ?? 0;

// Get top-selling products for the period
$topProductsQuery = "SELECT products.name, SUM(sale_items.quantity) as quantity_sold 
                     FROM sale_items 
                     JOIN products ON sale_items.product_id = products.id 
                     JOIN sales ON sale_items.sale_id = sales.id 
                     WHERE sales.sale_date BETWEEN ? AND ? 
                     GROUP BY products.name ORDER BY quantity_sold DESC LIMIT 5";
$stmt = $conn->prepare($topProductsQuery);
$stmt->bind_param("ss", $startDate, $endDate);
$stmt->execute();
$topProducts = $stmt->get_result();

// Get sales by employee
$salesByEmployeeQuery = "SELECT users.username, SUM(sales.total) as total_sales
                         FROM sales 
                         JOIN users ON sales.user_id = users.id
                         WHERE sales.sale_date BETWEEN ? AND ? 
                         GROUP BY users.username ORDER BY total_sales DESC";
$stmt = $conn->prepare($salesByEmployeeQuery);
$stmt->bind_param("ss", $startDate, $endDate);
$stmt->execute();
$salesByEmployee = $stmt->get_result();

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Sales Report - Inventory System</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <style>
    .card { margin-bottom: 20px; }
    .table th, .table td { text-align: center; }
  </style>
</head>
<body>

<div class="container mt-5">
  <h3 class="text-primary"><i class="bi bi-bar-chart-fill me-2"></i> Sales Report</h3>

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

<form method="GET" action="export-sales-report.php" class="mb-4">
    <div class="d-flex justify-content-between">
        <div>
        <!-- Existing date range filter form here -->
        </div>
        <div>
        <button type="submit" class="btn btn-success"><i class="bi bi-file-earmark-spreadsheet me-2"></i> Export to CSV</button>
        <a href="export-sales-report-pdf.php?start_date=<?= $startDate ?>&end_date=<?= $endDate ?>" class="btn btn-danger ms-3"><i class="bi bi-file-earmark-pdf me-2"></i> Export to PDF</a>
        </div>
    </div>
</form>


  <!-- Total Sales -->
  <div class="card">
    <div class="card-body">
      <h5>Total Sales for the Period</h5>
      <p class="h4">Rs. <?= number_format($totalSales, 2) ?></p>
    </div>
  </div>

  <!-- Top Selling Products -->
  <div class="card">
    <div class="card-body">
      <h5>Top Selling Products</h5>
      <table class="table table-bordered">
        <thead>
          <tr>
            <th>Product Name</th>
            <th>Quantity Sold</th>
          </tr>
        </thead>
        <tbody>
          <?php while ($product = $topProducts->fetch_assoc()): ?>
            <tr>
              <td><?= htmlspecialchars($product['name']) ?></td>
              <td><?= $product['quantity_sold'] ?></td>
            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>
  </div>

  <!-- Sales by Employee -->
  <div class="card">
    <div class="card-body">
      <h5>Sales by Employee</h5>
      <table class="table table-bordered">
        <thead>
          <tr>
            <th>Employee</th>
            <th>Total Sales</th>
          </tr>
        </thead>
        <tbody>
          <?php while ($employee = $salesByEmployee->fetch_assoc()): ?>
            <tr>
              <td><?= htmlspecialchars($employee['username']) ?></td>
              <td>Rs. <?= number_format($employee['total_sales'], 2) ?></td>
            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>
  </div>

  <!-- Sales Chart -->
  <div class="card">
    <div class="card-body">
      <h5>Sales Over Time</h5>
      <canvas id="salesChart" width="400" height="200"></canvas>
      <script>
        const ctx = document.getElementById('salesChart').getContext('2d');
        const salesChart = new Chart(ctx, {
          type: 'line',
          data: {
            labels: ['<?= date('Y-m-d', strtotime($startDate)) ?>', '<?= date('Y-m-d', strtotime($endDate)) ?>'],
            datasets: [{
              label: 'Sales (Rs)',
              data: [<?= $totalSales ?>, <?= $totalSales ?>], // Dynamic data goes here
              borderColor: 'rgba(75, 192, 192, 1)',
              backgroundColor: 'rgba(75, 192, 192, 0.2)',
              borderWidth: 1
            }]
          },
          options: {
            scales: {
              y: {
                beginAtZero: true
              }
            }
          }
        });
      </script>
    </div>
  </div>
</div>

</body>
</html>
