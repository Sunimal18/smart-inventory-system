<?php
session_start();
include '../../includes/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && $_SESSION['role'] === 'admin') {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $sku = $_POST['sku'];
    $price = $_POST['price'];
    $cost_price = $_POST['cost_price'];
    $quantity = $_POST['quantity'];
    $category = $_POST['category'];

    $stmt = $conn->prepare("UPDATE products SET name=?, sku=?, price=?, cost_price=?, quantity=?, category=? WHERE id=?");
    $stmt->bind_param("ssddisi", $name, $sku, $price, $cost_price, $quantity, $category, $id);

    if ($stmt->execute()) {
        header("Location: ../manage_product.php?msg=updated");
    } else {
        echo "Error updating product: " . $stmt->error;
    }
}
?>
