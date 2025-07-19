<?php
require_once __DIR__ . '/../includes/auth.php';
logout();
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Logging out...</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background: url('../assets/background image/izms-zppsu-bg.jpg') no-repeat center center fixed;
      background-size: cover;
    }
  </style>
</head>

<body>
  <div class="modal fade show" id="loadingModal" tabindex="-1" aria-modal="true" role="dialog"
    style="display:block; background:rgba(0,0,0,0.5);">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content bg-transparent border-0 shadow-none align-items-center">
        <div class="text-center">
          <img src="../assets/logo/zppsu_logo.png" alt="Logo" style="height:60px; margin-bottom:30px;">
          <div class="spinner-border text-danger" style="width: 3rem; height: 3rem;" role="status">
            <span class="visually-hidden">Loading...</span>
          </div>
          <div class="fw-bold text-danger mt-3">Logging out, please wait...</div>
        </div>
      </div>
    </div>
  </div>
  <script>
    setTimeout(function () {
      window.location.href = 'login.php';
    }, 3000);
  </script>
</body>

</html>