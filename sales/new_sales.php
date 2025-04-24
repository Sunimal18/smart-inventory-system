<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

include '../includes/db.php';

// Get all products from the database
$products = $conn->query("SELECT * FROM products");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>New Sale - Inventory System</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    .cart-item {
      border-bottom: 1px solid #ddd;
    }
    .total {
      font-size: 1.5rem;
      font-weight: bold;
    }
  </style>
</head>
<body>

<?php if (isset($_GET['msg']) && $_GET['msg'] == 'success'): ?>
  <div class="alert alert-success alert-dismissible fade show" role="alert">
    <strong>Success!</strong> Sale completed successfully!
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
<?php endif; ?>

<div class="container mt-5">
  <h3 class="text-primary"><i class="bi bi-cart-fill me-2"></i> New Sale</h3>
  <form action="sql/update_sale.php" method="POST">
    <div class="row">
      <!-- Product selection -->
      <div class="col-md-6">
        <label for="product_id" class="form-label">Select Product</label>
        <select name="product_id" id="product_id" class="form-control" required>
          <option value="">Select a product</option>
          <?php while ($row = $products->fetch_assoc()): ?>
            <option value="<?= $row['id'] ?>" data-price="<?= $row['price'] ?>" data-stock="<?= $row['quantity'] ?>">
              <?= htmlspecialchars($row['name']) ?> - Rs. <?= number_format($row['price'], 2) ?>
            </option>
          <?php endwhile; ?>
        </select>
      </div>

      <!-- Quantity input -->
      <div class="col-md-6">
        <label for="quantity" class="form-label">Quantity</label>
        <input type="number" name="quantity" id="quantity" class="form-control" min="1" value="1" required>
      </div>
    </div>

    <!-- Cart section -->
    <div class="mt-3">
      <h4>Cart</h4>
      <div id="cart"></div>
      <div class="total mt-3">Total: Rs. 0.00</div>
    </div>

    <!-- Hidden input for total value -->
    <input type="hidden" name="total" id="total">

    <button type="submit" class="btn btn-success mt-4">Complete Sale</button>
  </form>
</div>

<script>
  // Handle adding products to cart
  const productSelect = document.getElementById("product_id");
  const quantityInput = document.getElementById("quantity");
  const cartContainer = document.getElementById("cart");
  const totalContainer = document.querySelector(".total");
  const totalInput = document.getElementById("total");

  // Trigger cart update when product or quantity changes
  productSelect.addEventListener("change", updateCart);
  quantityInput.addEventListener("input", updateCart);  // Listen for quantity change

  function updateCart() {
    const product = productSelect.options[productSelect.selectedIndex];
    const productId = product.value;
    const productName = product.textContent;
    const productPrice = parseFloat(product.getAttribute("data-price"));
    const productStock = parseInt(product.getAttribute("data-stock"));
    const quantity = parseInt(quantityInput.value);

    if (productId) {
      // Check if enough stock is available
      if (quantity > productStock) {
        alert("Not enough stock available!");
        quantityInput.value = productStock; // Reset quantity to max stock
        return;
      }

      const totalPrice = productPrice * quantity;
      cartContainer.innerHTML = `
        <div class="cart-item">
          <div>${productName}</div>
          <div>Quantity: ${quantity}</div>
          <div>Total: Rs. ${totalPrice.toFixed(2)}</div>
        </div>
      `;
      totalContainer.innerHTML = `Total: Rs. ${totalPrice.toFixed(2)}`;
      totalInput.value = totalPrice.toFixed(2);  // Set the total value for the form submission
    }
  }
</script>

</body>
</html>
