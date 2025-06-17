<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>ZPPSU Faculty Evaluation System</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css?v=<?php echo time(); ?>"
        rel="stylesheet">
    <!-- Add Bootstrap Icons CDN -->
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css?v=<?php echo time(); ?>">
    <style>
        .card {
            background: rgba(255, 255, 255, 0.95);
        }

        .navbar.bg-danger {
            background-color: #800000 !important;
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
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor"
                    class="bi bi-clock me-2" viewBox="0 0 16 16">
                    <path d="M8 3.5a.5.5 0 0 1 .5.5v4h3a.5.5 0 0 1 0 1H8a.5.5 0 0 1-.5-.5V4a.5.5 0 0 1 .5-.5z" />
                    <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm0-1A7 7 0 1 1 8 1a7 7 0 0 1 0 14z" />
                </svg>
                <span id="currentTime"></span>
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor"
                    class="bi bi-calendar-event ms-3 me-2" viewBox="0 0 16 16">
                    <path
                        d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5zM1 3a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1v1H1V3zm0 2v9a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V5H1zm11 3.5a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-1 0v-2a.5.5 0 0 1 .5-.5z" />
                </svg>
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
    <script
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js?v=<?php echo time(); ?>"></script>
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