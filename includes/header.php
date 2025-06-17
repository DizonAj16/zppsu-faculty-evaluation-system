<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>ZPPSU Faculty Evaluation System</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
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
            <span class="ms-auto text-white fw-bold d-flex align-items-center" id="currentTimeWrapper">
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor"
                    class="bi bi-clock me-2" viewBox="0 0 16 16">
                    <path d="M8 3.5a.5.5 0 0 1 .5.5v4h3a.5.5 0 0 1 0 1H8a.5.5 0 0 1-.5-.5V4a.5.5 0 0 1 .5-.5z" />
                    <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm0-1A7 7 0 1 1 8 1a7 7 0 0 1 0 14z" />
                </svg>
                <span id="currentTime"></span>
                <span class="d-flex align-items-center ms-3 px-2 py-1 rounded calendar-bg-maroon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor"
                        class="bi bi-calendar me-2" viewBox="0 0 16 16">
                        <path
                            d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5zM1 4v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4H1zm1-1a1 1 0 0 0-1 1v1h14V4a1 1 0 0 0-1-1H2z" />
                    </svg>
                    <span id="currentDate"></span>
                </span>
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