<?php
require_once __DIR__ . '/../includes/auth.php';
include __DIR__ . '/../includes/header.php';

$message = '';
$errorType = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $loginResult = login($username, $password);
    if ($loginResult === true) {
        $role = get_role();
        // Show spinner before redirect
        $redirect = ($role === 'admin') ? 'admin/admin.php' : "{$role}.php";
        echo <<<HTML
        <div class="modal fade show" id="loadingModal" tabindex="-1" aria-modal="true" role="dialog" style="display:block; background:rgba(0,0,0,0.5);">
          <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content bg-transparent border-0 shadow-none align-items-center">
              <div class="text-center">
                <img src="../assets/logo/zppsu_logo.png" alt="Logo" style="height:60px; margin-bottom:30px;">
                <div class="spinner-border text-danger" style="width: 3rem; height: 3rem;" role="status">
                  <span class="visually-hidden">Loading...</span>
                </div>
                <div class="fw-bold text-danger mt-3">Loading, please wait...</div>
              </div>
            </div>
          </div>
        </div>
        <script>
        setTimeout(function() {
            window.location.href = '{$redirect}';
        }, 3000);
        </script>
        HTML;
        exit();
    } else {
        $message = $loginResult;
        // Determine error type for modal icon/color
        if (strpos($loginResult, 'not found') !== false) {
            $errorType = 'user';
        } elseif (strpos($loginResult, 'Incorrect password') !== false) {
            $errorType = 'password';
        } elseif (strpos($loginResult, 'Database error') !== false) {
            $errorType = 'db';
        } elseif (strpos($loginResult, 'required') !== false) {
            $errorType = 'validation';
        } else {
            $errorType = 'other';
        }
        // Show spinner, then error modal after delay
        echo <<<HTML
        <div class="modal fade show" id="loadingModal" tabindex="-1" aria-modal="true" role="dialog" style="display:block; background:rgba(0,0,0,0.5);">
          <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content bg-transparent border-0 shadow-none align-items-center">
              <div class="text-center">
                <img src="../assets/logo/zppsu_logo.png" alt="Logo" style="height:60px; margin-bottom:30px;">
                <div class="spinner-border text-danger" style="width: 3rem; height: 3rem;" role="status">
                  <span class="visually-hidden">Loading...</span>
                </div>
                <div class="fw-bold text-danger mt-3">Loading, please wait...</div>
              </div>
            </div>
          </div>
        </div>
        <div class="modal fade" id="alertModal" tabindex="-1" aria-labelledby="alertModalLabel" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
              <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="alertModalLabel">Login Error</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body text-center">
                <div class="mb-3">
                  <!-- Icon based on error type -->
HTML;
        // Only show the relevant icon for the error type
        if ($errorType === 'user') {
            echo <<<HTML
                  <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" fill="currentColor" class="bi bi-person-x-fill text-danger" viewBox="0 0 16 16">
                    <path d="M1 14s-1 0-1-1 1-4 7-4 7 3 7 4-1 1-1 1H1zm7-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm3.146-1.146a.5.5 0 0 1 .708 0l1 1a.5.5 0 0 1-.708.708L12.5 8.707l-.646.647a.5.5 0 0 1-.708-.708l.647-.646-.647-.646a.5.5 0 1 1 .708-.708l.646.647.646-.647z"/>
                  </svg>
HTML;
        } elseif ($errorType === 'password') {
            echo <<<HTML
                  <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" fill="currentColor" class="bi bi-lock-fill text-warning" viewBox="0 0 16 16">
                    <path d="M8 1a3 3 0 0 0-3 3v3H4a2 2 0 0 0-2 2v5a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2v-5a2 2 0 0 0-2-2h-1V4a3 3 0 0 0-3-3zm-2 3a2 2 0 1 1 4 0v3H6V4zm-2 5h8v5H4V9z"/>
                  </svg>
HTML;
        } elseif ($errorType === 'db') {
            echo <<<HTML
                  <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" fill="currentColor" class="bi bi-server text-secondary" viewBox="0 0 16 16">
                    <path d="M1 5a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1v2a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V5zm0 4a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1v2a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V9zm2 1a1 1 0 1 0 0 2 1 1 0 0 0 0-2zm0-6a1 1 0 1 0 0 2 1 1 0 0 0 0-2z"/>
                  </svg>
HTML;
        } elseif ($errorType === 'validation') {
            echo <<<HTML
                  <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" fill="currentColor" class="bi bi-exclamation-triangle-fill text-warning" viewBox="0 0 16 16">
                    <path d="M8.982 1.566a1.13 1.13 0 0 0-1.964 0L.165 13.233c-.457.778.091 1.767.982 1.767h13.707c.89 0 1.438-.99.982-1.767L8.982 1.566zm-.982 4.434a.905.905 0 1 1 1.81 0l-.35 3.5a.55.55 0 0 1-1.1 0l-.36-3.5zm.002 6a1 1 0 1 1 2 0 1 1 0 0 1-2 0z"/>
                  </svg>
HTML;
        } else {
            echo <<<HTML
                  <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" fill="currentColor" class="bi bi-exclamation-circle-fill text-danger" viewBox="0 0 16 16">
                    <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM7.002 5a1 1 0 1 1 2 0l-.35 4.5a.65.65 0 0 1-1.3 0L7.002 5zm1 7a1 1 0 1 1-2 0 1 1 0 0 1 2 0z"/>
                  </svg>
HTML;
        }
        echo <<<HTML
                </div>
                <div class="fs-5">{$message}</div>
              </div>
            </div>
          </div>
        </div>
        <script>
        setTimeout(function() {
            var alertModal = new bootstrap.Modal(document.getElementById('alertModal'));
            alertModal.show();
            // Hide spinner
            document.getElementById('loadingModal').style.display = 'none';
            setTimeout(function() {
                alertModal.hide();
                // Redirect to login after modal hides
                window.location.href = 'login.php';
            }, 5000);
        }, 2000);
        </script>
        HTML;
        exit();
    }
}
?>
<?php if ($message): ?>
<!-- Bootstrap Modal for Error -->
<div class="modal fade" id="alertModal" tabindex="-1" aria-labelledby="alertModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-danger text-white">
        <h5 class="modal-title" id="alertModalLabel">Error</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <?= $message ?>
      </div>
    </div>
  </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var alertModal = new bootstrap.Modal(document.getElementById('alertModal'));
    alertModal.show();
    setTimeout(function() {
        alertModal.hide();
        // Redirect to login after modal hides
        window.location.href = 'login.php';
    }, 5000);
});
</script>
<?php endif; ?>
<style>
/* Maroon button and eye icon */
.btn-maroon {
    background-color: #800000 !important;
    border-color: #800000 !important;
    color: #fff !important;
}
.btn-maroon:hover, .btn-maroon:focus {
    background-color: #a83232 !important;
    border-color: #a83232 !important;
    color: #fff !important;
}
.btn-eye-maroon {
    background-color: #800000 !important;
    border-color: #800000 !important;
    color: #fff !important;
}
.btn-eye-maroon:hover, .btn-eye-maroon:focus {
    background-color: #a83232 !important;
    border-color: #a83232 !important;
    color: #fff !important;
}
/* Stylize signup button */
.btn-signup-maroon {
    background-color: #fff !important;
    color: #800000 !important;
    border: 2px solid #800000 !important;
    border-radius: 25px;
    padding: 6px 24px;
    font-weight: 500;
    transition: all 0.2s;
    margin-top: 8px;
}
/* Maroon focus for input fields */
.form-control:focus, .input-group .form-control:focus {
    border-color: #800000 !important;
    box-shadow: 0 0 0 0.2rem rgba(128,0,0,0.15) !important;
}
</style>
<div class="row justify-content-center">
    <div class="col-md-4">
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-center mb-4">
                    <img src="../assets/logo/zppsu_logo.png" alt="Logo" style="height:40px;">
                    <div class="mx-3" style="border-left:2px solid #800000; height:32px;"></div>
                    <h2 class="card-title mb-0 text-center">Login</h2>
                </div>
                <form method="post">
                    <div class="mb-3">
                        <label class="form-label">Username</label>
                        <input type="text" name="username" class="form-control" required autocomplete="off">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <div class="input-group">
                            <input type="password" name="password" class="form-control" id="loginPassword" required>
                            <button class="btn btn-eye-maroon" type="button" id="toggleLoginPassword" tabindex="-1">
                                <!-- Eye open icon -->
                                <svg id="eyeOpenLogin" xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-eye" viewBox="0 0 16 16">
                                    <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13.133 13.133 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.133 13.133 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.12 12.5 8 12.5c-2.12 0-3.879-1.168-5.168-2.457A13.133 13.133 0 0 1 1.172 8z"/>
                                    <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zm0 1a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3z"/>
                                </svg>
                                <!-- Eye close icon (hidden by default) -->
                                <svg id="eyeCloseLogin" xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-eye-slash d-none" viewBox="0 0 16 16">
                                    <path d="M13.359 11.238C12.01 12.226 10.164 13.5 8 13.5c-2.12 0-3.879-1.168-5.168-2.457A13.133 13.133 0 0 1 1.172 8c.058-.087.122-.183.195-.288.335-.48.83-1.12 1.465-1.755C4.121 4.668 5.88 3.5 8 3.5c1.326 0 2.55.416 3.637 1.09l.708-.708A14.13 14.13 0 0 0 8 2.5C3 2.5 0 8 0 8s3 5.5 8 5.5c2.164 0 4.01-1.274 5.359-2.262l-.708-.708z"/>
                                    <path d="M11.297 9.115a3 3 0 0 1-4.412-4.412l4.412 4.412z"/>
                                    <path d="M13.646 14.354a.5.5 0 0 1-.708 0l-12-12a.5.5 0 1 1 .708-.708l12 12a.5.5 0 0 1 0 .708z"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-maroon w-100">Login</button>
                </form>
                <div class="text-center">
                    <a href="signup.php" class="btn btn-signup-maroon w-100">Signup</a>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
</body>
</html>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Password toggle for login
    const loginPassword = document.getElementById('loginPassword');
    const toggleLoginPassword = document.getElementById('toggleLoginPassword');
    const eyeOpenLogin = document.getElementById('eyeOpenLogin');
    const eyeCloseLogin = document.getElementById('eyeCloseLogin');
    if (toggleLoginPassword) {
        toggleLoginPassword.addEventListener('click', function() {
            if (loginPassword.type === 'password') {
                loginPassword.type = 'text';
                eyeOpenLogin.classList.add('d-none');
                eyeCloseLogin.classList.remove('d-none');
            } else {
                loginPassword.type = 'password';
                eyeOpenLogin.classList.remove('d-none');
                eyeCloseLogin.classList.add('d-none');
            }
        });
    }
});
</script>