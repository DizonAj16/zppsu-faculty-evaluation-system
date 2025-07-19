<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>ZPPSU Faculty Evaluation System</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap 5 CDN -->
    <link href="../assets/css/bootstrap.min.css?v=<?php echo time(); ?>" rel="stylesheet">

    <link href="../assets/css/bootstrap-icons-1.10.5/bootstrap-icons-1.10.5/font/bootstrap-icons.css?v=<?php echo time(); ?>" rel="stylesheet">
    <script src="../assets/js/bootstrap.bundle.min.js?v=<?php echo time(); ?>"></script>
    <style>
        body.bg-light {
            background-image: url('../assets/background image/izms-zppsu-bg.jpg');
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center center;
            min-height: 100vh;
        }

        /* Ensure maroon navbar always overrides Bootstrap's bg-danger */
        nav.navbar.bg-danger,
        .navbar.bg-danger,
        .bg-danger {
            background-color: #800000 !important;
        }

        .card {
            background: rgba(255, 255, 255, 0.95);
        }

        /* Ensure calendar icon background matches navbar */
        .calendar-bg-maroon {
            background-color: #800000 !important;
        }
    </style>
</head>

<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-dark bg-danger mb-5">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">
                <img src="../assets/logo/zppsu_logo.png" alt="ZPPSU Logo" style="height:40px;margin-right:10px;">
                ZPPSU Faculty Evaluation System
            </a>
            </a>
            <span class="ms-auto text-white fw-bold d-flex align-items-center" id="currentTimeWrapper">
                <i class="bi bi-clock me-2 fs-5"></i>
                <span id="currentTime"></span>
                <i class="bi bi-calendar-event ms-3 me-2 fs-5"></i>
                <span id="currentDate"></span>
            </span>
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
</body>

</html>