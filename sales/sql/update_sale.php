<?php
session_start();
include '../../includes/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION['user_id'])) {
    $productId = $_POST['product_id'];
    $quantity = $_POST['quantity'];
    $total = $_POST['total']; // Total price for this sale
    $userId = $_SESSION['user_id'];

    // Fetch product details to check stock and calculate total price
    $productResult = $conn->query("SELECT * FROM products WHERE id = $productId");
    $product = $productResult->fetch_assoc();

    if ($product['quantity'] < $quantity) {
        echo "Not enough stock!";
        exit();
    }

    // Record the sale
    $stmt = $conn->prepare("INSERT INTO sales (total, user_id) VALUES (?, ?)");
    $stmt->bind_param("di", $total, $userId);
    $stmt->execute();
    $saleId = $stmt->insert_id;

    // Insert the sale items
    $stmt = $conn->prepare("INSERT INTO sale_items (sale_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("iiid", $saleId, $productId, $quantity, $product['price']);
    $stmt->execute();

    // Update the product stock
    $newStock = $product['quantity'] - $quantity;
    $stmt = $conn->prepare("UPDATE products SET quantity = ? WHERE id = ?");
    $stmt->bind_param("ii", $newStock, $productId);
    $stmt->execute();

    // Redirect after successful sale
    header("Location: ../new_sales.php?msg=success");
} else {
    header("Location: ../new_sales.php?msg=error");
}
?>
