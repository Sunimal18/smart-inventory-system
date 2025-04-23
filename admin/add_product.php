<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Add Product - Inventory System</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body {
      background: #f4f6f9;
    }
    .card {
      border-radius: 1rem;
      box-shadow: 0 0 20px rgba(0, 0, 0, 0.05);
      margin-top: 30px;
    }
    .btn-primary {
      background: linear-gradient(90deg, #007bff, #6610f2);
      border: none;
    }
  </style>
</head>
<body>
<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-8">
      <div class="card p-4">
        <h3 class="mb-4 text-primary"><i class="bi bi-box-seam me-2"></i>Add New Product</h3>
        <form action="sql/insert_product.php" method="POST">
          <div class="mb-3">
            <label>Product Name</label>
            <input type="text" name="name" class="form-control" required>
          </div>

          <div class="mb-3">
            <label>SKU</label>
            <input type="text" name="sku" class="form-control" required>
          </div>

          <div class="row">
            <div class="col-md-6 mb-3">
              <label>Price (Selling)</label>
              <input type="number" name="price" step="0.01" class="form-control" required>
            </div>
            <div class="col-md-6 mb-3">
              <label>Cost Price</label>
              <input type="number" name="cost_price" step="0.01" class="form-control" required>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6 mb-3">
              <label>Quantity</label>
              <input type="number" name="quantity" class="form-control" required>
            </div>
            <div class="col-md-6 mb-3">
              <label>Category</label>
              <input type="text" name="category" class="form-control">
            </div>
          </div>

          <div class="d-flex justify-content-between">
            <a href="../dashboard.php" class="btn btn-secondary">Cancel</a>
            <button type="submit" class="btn btn-primary">Add Product</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
</body>
</html>
