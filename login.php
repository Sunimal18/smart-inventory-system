<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Login - Inventory System</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body {
      background: linear-gradient(135deg, #007bff, #6610f2);
      height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
    }
    .card {
      border-radius: 1rem;
      box-shadow: 0 0 30px rgba(0,0,0,0.2);
      animation: fadeIn 1s ease;
    }
    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(20px); }
      to { opacity: 1; transform: translateY(0); }
    }
    .form-control:focus {
      box-shadow: 0 0 5px #5f27cd;
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-5">
        <div class="card p-4 bg-white">
          <h4 class="text-center mb-4 text-primary fw-bold">Inventory Login</h4>

          <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
          <?php endif; ?>

          <form action="sql/authenticate.php" method="POST">
            <div class="mb-3">
              <label class="form-label"><i class="bi bi-person-fill me-2"></i>Username</label>
              <input type="text" name="username" class="form-control" required>
            </div>
            <div class="mb-3">
              <label class="form-label"><i class="bi bi-lock-fill me-2"></i>Password</label>
              <input type="password" name="password" class="form-control" required>
            </div>
            <button class="btn btn-primary w-100 fw-bold">Login</button>
          </form>

          <p class="mt-3 text-center text-muted" style="font-size: 14px;">
            © <?= date('Y') ?> Smart Inventory System
          </p>
        </div>
      </div>
    </div>
  </div>
</body>
</html>
