<?php
session_start();
include '../../includes/db.php';

// Only allow admins to delete
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../../login.php");
    exit();
}

// Check if ID is passed
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    // Prepare and delete product
    $stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        header("Location: ../manage_product.php?msg=deleted");
    } else {
        echo "<div class='alert alert-danger'>Error deleting product: " . $stmt->error . "</div>";
    }
} else {
    header("Location: ../manage_product.php");
}
?>
