<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}
include '../includes/db.php';

if (!isset($_GET['id'])) {
    header("Location: manage-products.php");
    exit();
}

$id = $_GET['id'];
$stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$product = $stmt->get_result()->fetch_assoc();

if (!$product) {
    echo "<div class='alert alert-danger'>Product not found.</div>";
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Edit Product - Inventory</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body { background: #f7f9fc; }
    .card {
      border-radius: 1rem;
      box-shadow: 0 0 20px rgba(0,0,0,0.05);
      margin-top: 30px;
    }
  </style>
</head>
<body>

<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-8">
      <div class="card p-4">
        <h3 class="mb-4 text-primary"><i class="bi bi-pencil-square me-2"></i>Edit Product</h3>
        <form action="sql/update_product.php" method="POST">
          <input type="hidden" name="id" value="<?= $product['id'] ?>">

          <div class="mb-3">
            <label>Product Name</label>
            <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($product['name']) ?>" required>
          </div>

          <div class="mb-3">
            <label>SKU</label>
            <input type="text" name="sku" class="form-control" value="<?= htmlspecialchars($product['sku']) ?>" required>
          </div>

          <div class="row">
            <div class="col-md-6 mb-3">
              <label>Price</label>
              <input type="number" name="price" step="0.01" class="form-control" value="<?= $product['price'] ?>" required>
            </div>
            <div class="col-md-6 mb-3">
              <label>Cost Price</label>
              <input type="number" name="cost_price" step="0.01" class="form-control" value="<?= $product['cost_price'] ?>" required>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6 mb-3">
              <label>Quantity</label>
              <input type="number" name="quantity" class="form-control" value="<?= $product['quantity'] ?>" required>
            </div>
            <div class="col-md-6 mb-3">
              <label>Category</label>
              <input type="text" name="category" class="form-control" value="<?= htmlspecialchars($product['category']) ?>">
            </div>
          </div>

          <div class="d-flex justify-content-between">
            <a href="manage_product.php" class="btn btn-secondary">Back</a>
            <button type="submit" class="btn btn-primary">Update Product</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

</body>
</html>
