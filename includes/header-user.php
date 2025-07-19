<?php
require_once '../../config/db.php';
$added = false;
if (isset($_GET['subject']) && $_GET['subject'] === 'success') {
    $added = true;
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ZPPSU Faculty Evaluation System</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap 5 CDN -->
    <link href="../../assets/css/bootstrap.min.css?v=<?php echo time(); ?>" rel="stylesheet">

    <link
        href="../../assets/css/bootstrap-icons-1.10.5/bootstrap-icons-1.10.5/font/bootstrap-icons.css?v=<?php echo time(); ?>"
        rel="stylesheet">
    <script src="../../assets/js/main.js" defer></script>
    <script src="../../assets/js/arjec.js" defer></script>

    <script>
        const added = <?php echo json_encode($added); ?>;
    </script>
    <style>
        .card {
            background: rgba(255, 255, 255, 0.95);
        }

        .navbar.bg-danger {
            background-color: #800000 !important;
        }

        .toast {
            animation: slideInRight 0.6s ease forwards;
        }

        @keyframes slideInRight {
            0% {
                opacity: 0;
                transform: translateX(100%);
            }

            100% {
                opacity: 1;
                transform: translateX(0);
            }
        }
    </style>
</head>

<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-dark bg-danger border-bottom">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">
                <img src="../../assets/logo/zppsu_logo.png" alt="ZPPSU Logo" style="height:40px;margin-right:10px;">
                ZPPSU Faculty Evaluation System
            </a>
            <span class="ms-auto text-white fw-bold d-flex align-items-center" id="currentTimeWrapper">
                <i class="bi bi-clock me-2 fs-5"></i>
                <span id="currentTime"></span>
                <i class="bi bi-calendar-event ms-3 me-2 fs-5"></i>
                <span id="currentDate"></span>
            </span>

            <div class="dropdown ms-3">
                <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle"
                    id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false" role="button" tabindex="0">
                    <img src="../../assets/profile/default.jpg" alt="Profile" width="32" height="32"
                        class="rounded-circle me-2"
                        onerror="this.onerror=null;this.src='https://ui-avatars.com/api/?name=Admin&background=800000&color=fff';">
                    <span class="d-none d-md-inline">admin</span>
                </a>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                    <li>
                        <a class="dropdown-item" href="#">
                            <i class="bi bi-person-circle me-2"></i>Profile
                        </a>
                    </li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li>
                        <a class="dropdown-item text-danger" href="../../public/logout.php">
                            <i class="bi bi-box-arrow-right me-2"></i>Logout
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container"></div>
    <script>
        function updateTime() {
            const now = new Date();
            let hours = now.getHours();
            const minutes = now.getMinutes();
            const seconds = now.getSeconds();
            const ampm = hours >= 12 ? 'PM' : 'AM';
            hours = hours % 12;
            hours = hours ? hours : 12; // 0 should be 12
            const pad = n => n.toString().padStart(2, '0');
            const timeString = `${pad(hours)}:${pad(minutes)}:${pad(seconds)} ${ampm}`;
            document.getElementById('currentTime').textContent = timeString;

            // Date format: Month Day, Year (e.g., April 27, 2024)
            const options = { year: 'numeric', month: 'long', day: 'numeric' };
            const dateString = now.toLocaleDateString(undefined, options);
            document.getElementById('currentDate').textContent = dateString;
        }
        document.addEventListener('DOMContentLoaded', function () {
            updateTime();
            setInterval(updateTime, 1000);
        });
    </script>
    <!-- Move Bootstrap JS here to ensure dropdowns work -->
    <script src="../../assets/js/bootstrap.bundle.min.js?v=<?php echo time(); ?>"></script>
    <script>
        // Explicitly initialize dropdown for user menu
        document.addEventListener('DOMContentLoaded', function () {
            var dropdownTrigger = document.getElementById('userDropdown');
            if (dropdownTrigger) {
                new bootstrap.Dropdown(dropdownTrigger);
            }
        });
    </script>
</body>

</html>