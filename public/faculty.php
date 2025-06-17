<?php
require_once __DIR__ . '/../includes/auth.php';
require_role('faculty');
include __DIR__ . '/../includes/header.php';
?>
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="alert alert-info text-center">
            <h2>Welcome, Faculty!</h2>
            <a href="logout.php" class="btn btn-danger mt-3">Logout</a>
        </div>
    </div>
</div>
</div>
</body>
</html>
