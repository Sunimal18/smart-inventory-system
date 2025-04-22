<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
include 'includes/db.php';

// Quick summary counts
$totalProducts = $conn->query("SELECT COUNT(*) as total FROM products")->fetch_assoc()['total'];
$totalSales = $conn->query("SELECT COUNT(*) as total FROM sales")->fetch_assoc()['total'];
$lowStock = $conn->query("SELECT COUNT(*) as total FROM products WHERE quantity < 10")->fetch_assoc()['total'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Dashboard - Inventory System</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body {
      background: #f4f4f4;
    }
    .navbar {
      background: linear-gradient(90deg, #007bff, #6610f2);
    }
    .navbar-brand, .nav-link, .text-white {
      color: #fff !important;
    }
    .card {
      border: none;
      border-radius: 10px;
      transition: all 0.3s ease;
    }
    .card:hover {
      transform: translateY(-5px);
      box-shadow: 0 5px 20px rgba(0,0,0,0.1);
    }
    .summary-icon {
      font-size: 2.5rem;
    }
  </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark px-4">
  <a class="navbar-brand fw-bold" href="#">Smart Inventory</a>
  <div class="ms-auto">
    <span class="text-white me-3">👋 Welcome, <?= $_SESSION['username'] ?> (<?= $_SESSION['role'] ?>)</span>
    <a href="logout.php" class="btn btn-sm btn-light">Logout</a>
  </div>
</nav>

<div class="container mt-5">
  <div class="row g-4">

    <!-- Total Products -->
    <div class="col-md-4">
      <div class="card bg-primary text-white p-4">
        <div class="d-flex justify-content-between">
          <div>
            <h5>Total Products</h5>
            <h2><?= $totalProducts ?></h2>
          </div>
          <i class="bi bi-box-seam summary-icon"></i>
        </div>
      </div>
    </div>

    <!-- Total Sales -->
    <div class="col-md-4">
      <div class="card bg-success text-white p-4">
        <div class="d-flex justify-content-between">
          <div>
            <h5>Total Sales</h5>
            <h2><?= $totalSales ?></h2>
          </div>
          <i class="bi bi-cart-check summary-icon"></i>
        </div>
      </div>
    </div>

    <!-- Low Stock Alerts -->
    <div class="col-md-4">
      <div class="card bg-danger text-white p-4">
        <div class="d-flex justify-content-between">
          <div>
            <h5>Low Stock Items</h5>
            <h2><?= $lowStock ?></h2>
          </div>
          <i class="bi bi-exclamation-triangle summary-icon"></i>
        </div>
      </div>
    </div>
  </div>

  <!-- Action Buttons -->
  <div class="row mt-5">
    <div class="col text-center">
      <a href="admin/manage-products.php" class="btn btn-outline-primary btn-lg me-3">
        <i class="bi bi-plus-circle"></i> Add Product
      </a>
      <a href="sales/new-sale.php" class="btn btn-outline-success btn-lg me-3">
        <i class="bi bi-cash-coin"></i> New Sale
      </a>
      <a href="sales/sales-history.php" class="btn btn-outline-dark btn-lg">
        <i class="bi bi-bar-chart"></i> View Reports
      </a>
    </div>
  </div>
</div>

</body>
</html>
