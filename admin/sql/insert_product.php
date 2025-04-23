<?php
session_start();
include '../../includes/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && $_SESSION['role'] == 'admin') {
    $name = $_POST['name'];
    $sku = $_POST['sku'];
    $price = $_POST['price'];
    $cost_price = $_POST['cost_price'];
    $quantity = $_POST['quantity'];
    $category = $_POST['category'];

    $stmt = $conn->prepare("INSERT INTO products (name, sku, price, cost_price, quantity, category) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssddis", $name, $sku, $price, $cost_price, $quantity, $category);

    if ($stmt->execute()) {
        // echo "<script>alert('Product adding successful!');</script>";
        header("Location: ../manage_product.php?msg=success");
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>
