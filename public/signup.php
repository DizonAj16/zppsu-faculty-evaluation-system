<?php
require_once __DIR__ . '/../includes/auth.php';
include __DIR__ . '/../includes/header.php';

$message = '';
$isSuccess = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $role = $_POST['role'];
    $result = signup($username, $password, $role);
    if ($result === true) {
        $message = "Signup successful. <a href='login.php'>Login here</a>.";
        $isSuccess = true;
    } else {
        $message = $result;
    }
}
?>
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
/* Stylize back to login button */
.btn-signup-maroon {
    background-color: #fff !important;
    color: #800000 !important;
    border: 2px solid #800000 !important;
    border-radius: 25px;
    padding: 6px 24px;
    font-weight: 500;
    transition: all 0.2s;
    margin-top: 8px;
    display: inline-block;
    text-decoration: none !important;
}
/* Maroon focus for input fields */
.form-control:focus, .input-group .form-control:focus, .form-select:focus {
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
                    <h2 class="card-title mb-0 text-center">Signup</h2>
                </div>
                <form method="post">
                    <div class="mb-3">
                        <label class="form-label">Username</label>
                        <input type="text" name="username" class="form-control" required autocomplete="off">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <div class="input-group">
                            <input type="password" name="password" class="form-control" id="signupPassword" required>
                            <button class="btn btn-eye-maroon" type="button" id="toggleSignupPassword" tabindex="-1">
                                <!-- Eye open icon -->
                                <svg id="eyeOpenSignup" xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-eye" viewBox="0 0 16 16">
                                    <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13.133 13.133 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.133 13.133 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.12 12.5 8 12.5c-2.12 0-3.879-1.168-5.168-2.457A13.133 13.133 0 0 1 1.172 8z"/>
                                    <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zm0 1a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3z"/>
                                </svg>
                                <!-- Eye close icon (hidden by default) -->
                                <svg id="eyeCloseSignup" xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-eye-slash d-none" viewBox="0 0 16 16">
                                    <path d="M13.359 11.238C12.01 12.226 10.164 13.5 8 13.5c-2.12 0-3.879-1.168-5.168-2.457A13.133 13.133 0 0 1 1.172 8c.058-.087.122-.183.195-.288.335-.48.83-1.12 1.465-1.755C4.121 4.668 5.88 3.5 8 3.5c1.326 0 2.55.416 3.637 1.09l.708-.708A14.13 14.13 0 0 0 8 2.5C3 2.5 0 8 0 8s3 5.5 8 5.5c2.164 0 4.01-1.274 5.359-2.262l-.708-.708z"/>
                                    <path d="M11.297 9.115a3 3 0 0 1-4.412-4.412l4.412 4.412z"/>
                                    <path d="M13.646 14.354a.5.5 0 0 1-.708 0l-12-12a.5.5 0 1 1 .708-.708l12 12a.5.5 0 0 1 0 .708z"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Role</label>
                        <select name="role" class="form-select">
                            <option value="faculty">Faculty</option>
                            <option value="student">Student</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-maroon w-100">Signup</button>
                </form>
                <div class="text-center">
                    <a href="login.php" class="btn btn-signup-maroon w-100">Back to Login</a>
                </div>
            </div>
        </div>
    </div>
</div>
<?php if ($message): ?>
<!-- Bootstrap Modal for Alert -->
<div class="modal fade" id="alertModal" tabindex="-1" aria-labelledby="alertModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header <?= $isSuccess ? 'bg-success' : 'bg-danger' ?> text-white">
        <h5 class="modal-title" id="alertModalLabel"><?= $isSuccess ? 'Success' : 'Error' ?></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body text-center">
        <div class="icon-animate">
          <?php if ($isSuccess): ?>
            <!-- Success check icon (Bootstrap 5 SVG) -->
            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" fill="currentColor" class="bi bi-check-circle-fill icon-success" viewBox="0 0 16 16">
              <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM6.97 11.03a.75.75 0 0 0 1.07 0l3.992-3.992a.75.75 0 1 0-1.06-1.06L7.5 9.439 6.03 7.97a.75.75 0 1 0-1.06 1.06l1.999 2z"/>
            </svg>
          <?php else: ?>
            <!-- Error exclamation icon (Bootstrap 5 SVG) -->
            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" fill="currentColor" class="bi bi-exclamation-circle-fill icon-error" viewBox="0 0 16 16">
              <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM7.002 5a1 1 0 1 1 2 0l-.35 4.5a.65.65 0 0 1-1.3 0L7.002 5zm1 7a1 1 0 1 1-2 0 1 1 0 0 1 2 0z"/>
            </svg>
          <?php endif; ?>
        </div>
        <div class="fs-5">
          <?= $message ?>
        </div>
      </div>
    </div>
  </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var alertModal = new bootstrap.Modal(document.getElementById('alertModal'));
    alertModal.show();
});
</script>
<?php endif; ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Password toggle for signup
    const signupPassword = document.getElementById('signupPassword');
    const toggleSignupPassword = document.getElementById('toggleSignupPassword');
    const eyeOpenSignup = document.getElementById('eyeOpenSignup');
    const eyeCloseSignup = document.getElementById('eyeCloseSignup');
    if (toggleSignupPassword) {
        toggleSignupPassword.addEventListener('click', function() {
            if (signupPassword.type === 'password') {
                signupPassword.type = 'text';
                eyeOpenSignup.classList.add('d-none');
                eyeCloseSignup.classList.remove('d-none');
            } else {
                signupPassword.type = 'password';
                eyeOpenSignup.classList.remove('d-none');
                eyeCloseSignup.classList.add('d-none');
            }
        });
    }
});
</script>
</body>
</html>