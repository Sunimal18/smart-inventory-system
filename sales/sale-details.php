<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

include '../includes/db.php';

if (!isset($_GET['id'])) {
    header("Location: sales-history.php");
    exit();
}

$saleId = $_GET['id'];

// Get sale details
$query = "SELECT sales.id, sales.sale_date, sales.total, users.username 
          FROM sales 
          JOIN users ON sales.user_id = users.id 
          WHERE sales.id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $saleId);
$stmt->execute();
$sale = $stmt->get_result()->fetch_assoc();

// Get sale items
$query = "SELECT sale_items.quantity, sale_items.price, products.name 
          FROM sale_items 
          JOIN products ON sale_items.product_id = products.id 
          WHERE sale_items.sale_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $saleId);
$stmt->execute();
$saleItems = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Sale Details - Inventory System</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
  <h3 class="text-primary"><i class="bi bi-file-earmark-check me-2"></i> Sale Details</h3>

  <div class="mb-4">
    <strong>Sale ID:</strong> <?= $sale['id'] ?><br>
    <strong>Sale Date:</strong> <?= date('Y-m-d', strtotime($sale['sale_date'])) ?><br>
    <strong>Salesperson:</strong> <?= htmlspecialchars($sale['username']) ?><br>
    <strong>Total Sale Amount:</strong> Rs. <?= number_format($sale['total'] ?? 0, 2) ?>
  </div>

  <h4>Products Sold</h4>
  <div class="table-responsive">
    <table class="table table-bordered">
      <thead class="table-primary">
        <tr>
          <th>Product</th>
          <th>Quantity</th>
          <th>Price</th>
          <th>Total</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($item = $saleItems->fetch_assoc()): ?>
          <tr>
            <td><?= htmlspecialchars($item['name']) ?></td>
            <td><?= $item['quantity'] ?></td>
            <td>Rs. <?= number_format($item['price'] * $item['quantity'] ?? 0, 2) ?></td>
            <td>Rs. <?= number_format($item['price'] * $item['quantity'], 2) ?></td>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>
  
  <a href="sales-history.php" class="btn btn-primary">Back to Sales History</a>
</div>

</body>
</html>
