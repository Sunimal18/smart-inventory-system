<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

include '../includes/db.php';

$products = $conn->query("SELECT * FROM products ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Manage Products</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body {
      background: #f4f4f4;
    }
    .badge-low {
      background: #dc3545;
    }
    .badge-ok {
      background: #28a745;
    }
  </style>
</head>
<body>

<div class="container mt-5">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="text-primary"><i class="bi bi-boxes me-2"></i>Product List</h3>
    <a href="add_product.php" class="btn btn-primary"><i class="bi bi-plus-circle me-1"></i> Add New Product</a>
  </div>

  <?php if (isset($_GET['msg']) && $_GET['msg'] == 'success'): ?>
    <div class="alert alert-success">Product successfully added!</div>
  <?php endif; ?>

  <?php if (isset($_GET['msg']) && $_GET['msg'] == 'updated'): ?>
    <div class="alert alert-success">Product updated successfully!</div>
  <?php endif; ?>

  <?php if (isset($_GET['msg']) && $_GET['msg'] == 'deleted'): ?>
    <div class="alert alert-success">Product deleted successfully!</div>
  <?php endif; ?>


  <div class="table-responsive">
    <table class="table table-bordered table-hover bg-white">
      <thead class="table-primary">
        <tr>
          <th>#</th>
          <th>Name</th>
          <th>SKU</th>
          <th>Price</th>
          <th>Cost</th>
          <th>Stock</th>
          <th>Category</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php $i = 1; while($row = $products->fetch_assoc()): ?>
          <tr>
            <td><?= $i++ ?></td>
            <td><?= htmlspecialchars($row['name']) ?></td>
            <td><?= htmlspecialchars($row['sku']) ?></td>
            <td>Rs. <?= number_format($row['price'], 2) ?></td>
            <td>Rs. <?= number_format($row['cost_price'], 2) ?></td>
            <td>
              <span class="badge <?= $row['quantity'] < 10 ? 'badge-low' : 'badge-ok' ?>">
                <?= $row['quantity'] ?>
              </span>
            </td>
            <td><?= htmlspecialchars($row['category']) ?></td>
            <td>
              <a href="edit_product.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-warning">
                <i class="bi bi-pencil-square"></i>
              </a>
              <a href="sql/delete_product.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure to delete?')">
                <i class="bi bi-trash3"></i>
              </a>
            </td>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>
</div>

</body>
</html>
