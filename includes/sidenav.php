<!-- Sidenav Start -->
<style>
    #sidebarMenu {
        min-height: 100vh;
        width: 250px;
        transition: left 0.3s, width 0.3s;
        background: #800000 !important;
        color: #fff !important;
    }

    #sidebarMenu.collapsed {
        width: 80px;
    }

    #sidebarMenu .nav-link,
    #sidebarMenu .nav-link i,
    #sidebarMenu .sidebar-brand-text,
    #sidebarMenu .sidebar-brand-icon {
        color: #fff !important;
    }

    #sidebarMenu .nav-link.active,
    #sidebarMenu .nav-link:hover {
        background: rgba(255, 255, 255, 0.08);
        color: #fff !important;
    }

    #sidebarMenu .nav-link.active i,
    #sidebarMenu .nav-link:hover i {
        color: #FFD700 !important;
        /* gold */
    }

    #sidebarMenu .nav-link span,
    #sidebarMenu .sidebar-brand-text {
        transition: opacity 0.2s;
    }

    /* Hide labels completely when collapsed */
    #sidebarMenu.collapsed .nav-link span,
    #sidebarMenu.collapsed .sidebar-brand-text {
        display: none !important;
        /* Remove opacity/pointer-events, use display:none for no space */
    }

    #sidebarMenu .sidebar-brand-icon {
        transition: margin-right 0.2s;
    }

    #sidebarMenu.collapsed .sidebar-brand-icon {
        margin-right: 0;
    }

    #sidebarCollapseBtn,
    #sidebarCollapseBtn i {
        color: #fff !important;
    }

    #sidebarMenu .nav-link i {
        font-size: 1.45em !important;
        vertical-align: middle;
    }

    #sidebarCollapseBtn i {
        font-size: 1.5em !important;
    }

    #sidebarMenu .nav-link {
        transition: background 0.25s ease-in, color 0.25s ease-in;
        opacity: 0;
        transform: translateX(-30px);
        animation: navItemSlideIn 0.5s forwards;
    }

    /* Stagger animation for each nav item */
    #sidebarMenu .nav li:nth-child(1) .nav-link {
        animation-delay: 0.05s;
    }

    #sidebarMenu .nav li:nth-child(2) .nav-link {
        animation-delay: 0.10s;
    }

    #sidebarMenu .nav li:nth-child(3) .nav-link {
        animation-delay: 0.15s;
    }

    #sidebarMenu .nav li:nth-child(4) .nav-link {
        animation-delay: 0.20s;
    }

    #sidebarMenu .nav li:nth-child(5) .nav-link {
        animation-delay: 0.25s;
    }

    #sidebarMenu .nav li:nth-child(6) .nav-link {
        animation-delay: 0.30s;
    }

    #sidebarMenu .nav li:nth-child(7) .nav-link {
        animation-delay: 0.35s;
    }

    #sidebarMenu .nav li:nth-child(8) .nav-link {
        animation-delay: 0.40s;
    }

    #sidebarMenu .nav li:nth-child(9) .nav-link {
        animation-delay: 0.45s;
    }

    @keyframes navItemSlideIn {
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    /* Only animate when .nav-animate class is present */
    #sidebarMenu .nav:not(.nav-animate) .nav-link {
        animation: none !important;
        opacity: 1;
        transform: none;
    }

    /* Layout for sidebar + dashboard */
    #adminLayout {
        min-height: 100vh;
        width: 100%;
    }

    #sidebarContainer {
        width: 250px;
        transition: width 0.3s cubic-bezier(.4, 0, .2, 1);
    }

    #sidebarMenu.collapsed+#dashboardContainer,
    #sidebarContainer.collapsed+#dashboardContainer {
        /* fallback if needed */
        margin-left: 50px;
    }


    #dashboardContainer {
        transition: margin-left 0.3s cubic-bezier(.4, 0, .2, 1);
        width: 100%;
    }

    /* When sidebar is collapsed, shrink sidebarContainer */
    #sidebarMenu.collapsed,
    #sidebarContainer.collapsed {
        width: 80px !important;
    }

    /* Responsive: match collapsed width on mobile */
    @media (max-width: 991.98px) {
        #sidebarContainer {
            width: 0 !important;
            transition: width 0.3s cubic-bezier(.4, 0, .2, 1);
        }

        #sidebarMenu.show {
            left: 0;
        }
    }
</style>
<div class="d-lg-none">
    <button class="btn btn-danger m-2" id="sidebarToggle">
        <i class="bi bi-list"></i>
    </button>
</div>
<div id="sidebarOverlay"></div>
<nav id="sidebarMenu" class="border-end d-lg-block">
    <div class="p-3 d-flex flex-column h-100">
        <div class="d-flex align-items-center mb-4">
            <!-- Optionally add logo here if needed -->
            <button class="btn btn-link ms-auto d-none d-lg-inline p-0" id="sidebarCollapseBtn"
                style="font-size:1.2rem;" data-bs-toggle="tooltip" data-bs-placement="top" title="Collapse sidebar">
                <i class="bi bi-chevron-left" id="collapseIcon"></i>
            </button>
        </div>
        <ul class="nav flex-column flex-grow-1">
            <li class="nav-item">
                <a class="nav-link" href="#" data-page="dashboard" data-bs-toggle="tooltip" data-bs-placement="top"
                    title="Dashboard"><i class="bi bi-house me-2"></i><span>Dashboard</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#" data-page="faculty" data-bs-toggle="tooltip" data-bs-placement="top"
                    title="Faculty"><i class="bi bi-people me-2"></i><span>Faculty</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#" data-page="programs" data-bs-toggle="tooltip" data-bs-placement="top"
                    title="Programs"><i class="bi bi-journal-code me-2"></i><span>Programs</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#" data-page="subjects" data-bs-toggle="tooltip" data-bs-placement="top"
                    title="Subjects"><i class="bi bi-book me-2"></i><span>Subjects</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#" data-page="departments" data-bs-toggle="tooltip" data-bs-placement="top"
                    title="Departments"><i class="bi bi-building me-2"></i><span>Departments</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#" data-page="academic-year" data-bs-toggle="tooltip" data-bs-placement="top"
                    title="Academic Year"><i class="bi bi-calendar3 me-2"></i><span>Academic Year</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#" data-page="year-section" data-bs-toggle="tooltip" data-bs-placement="top"
                    title="Year and Section"><i class="bi bi-collection me-2"></i><span>Year and Section</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#" data-page="faculty-evaluation" data-bs-toggle="tooltip"
                    data-bs-placement="top" title="Faculty Evaluation"><i
                        class="bi bi-clipboard-check me-2"></i><span>Faculty
                        Evaluation</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#" data-page="settings" data-bs-toggle="tooltip" data-bs-placement="top"
                    title="Settings"><i class="bi bi-gear me-2"></i><span>Settings</span></a>
            </li>
        </ul>
    </div>
</nav>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const sidebarMenu = document.getElementById('sidebarMenu');
        const sidebarToggle = document.getElementById('sidebarToggle');
        const sidebarOverlay = document.getElementById('sidebarOverlay');
        const sidebarCollapseBtn = document.getElementById('sidebarCollapseBtn');
        const collapseIcon = document.getElementById('collapseIcon');
        const sidebarContainer = document.getElementById('sidebarContainer');

        function closeSidebar() {
            sidebarMenu.classList.remove('show');
            sidebarOverlay.classList.remove('active');
        }
        function openSidebar() {
            sidebarMenu.classList.add('show');
            sidebarOverlay.classList.add('active');
        }
        function toggleSidebar() {
            sidebarMenu.classList.toggle('show');
            sidebarOverlay.classList.toggle('active');
        }
        function toggleCollapse() {
            sidebarMenu.classList.toggle('collapsed');
            if (sidebarContainer) {
                sidebarContainer.classList.toggle('collapsed');
            }
            // Change icon direction
            if (sidebarMenu.classList.contains('collapsed')) {
                collapseIcon.classList.remove('bi-chevron-left');
                collapseIcon.classList.add('bi-chevron-right');
            } else {
                collapseIcon.classList.remove('bi-chevron-right');
                collapseIcon.classList.add('bi-chevron-left');
            }
            handleSidebarCollapseTooltip();
        }
        if (sidebarToggle) {
            sidebarToggle.addEventListener('click', toggleSidebar);
        }
        if (sidebarOverlay) {
            sidebarOverlay.addEventListener('click', closeSidebar);
        }
        if (sidebarCollapseBtn) {
            sidebarCollapseBtn.addEventListener('click', function (e) {
                e.preventDefault();
                toggleCollapse();
            });
        }
        // Close sidebar on resize to lg and up
        window.addEventListener('resize', function () {
            if (window.innerWidth >= 992) closeSidebar();
            handleSidebarCollapseTooltip();
        });
        // Responsive: auto-collapse on small screens
        function handleResize() {
            if (window.innerWidth < 992) {
                sidebarMenu.classList.remove('collapsed');
                if (sidebarContainer) {
                    sidebarContainer.classList.remove('collapsed');
                }
                collapseIcon.classList.remove('bi-chevron-right');
                collapseIcon.classList.add('bi-chevron-left');
            }
        }
        window.addEventListener('resize', handleResize);
        handleResize();

        // Trigger nav item animation on load
        var navList = document.querySelector('#sidebarMenu .nav');
        if (navList) {
            navList.classList.add('nav-animate');
        }

        // Tooltip logic for collapsed sidebar
        let tooltipInstances = [];
        let collapseBtnTooltip = null;

        function enableNavTooltips() {
            const navLinks = document.querySelectorAll('#sidebarMenu .nav-link');
            navLinks.forEach(link => {
                if (!link._tooltipInstance) {
                    link._tooltipInstance = new bootstrap.Tooltip(link, { placement: 'top' });
                    tooltipInstances.push(link._tooltipInstance);
                }
            });
        }
        function disableNavTooltips() {
            tooltipInstances.forEach(instance => instance.dispose());
            tooltipInstances = [];
            const navLinks = document.querySelectorAll('#sidebarMenu .nav-link');
            navLinks.forEach(link => { link._tooltipInstance = null; });
        }
        function handleSidebarCollapseTooltip() {
            if (sidebarMenu.classList.contains('collapsed')) {
                enableNavTooltips();
                // Update collapse button tooltip to "Expand sidebar"
                if (collapseBtnTooltip) collapseBtnTooltip.dispose();
                sidebarCollapseBtn.setAttribute('title', 'Expand sidebar');
                collapseBtnTooltip = new bootstrap.Tooltip(sidebarCollapseBtn, { placement: 'top' });
            } else {
                disableNavTooltips();
                // Update collapse button tooltip to "Collapse sidebar"
                if (collapseBtnTooltip) collapseBtnTooltip.dispose();
                sidebarCollapseBtn.setAttribute('title', 'Collapse sidebar');
                collapseBtnTooltip = new bootstrap.Tooltip(sidebarCollapseBtn, { placement: 'top' });
            }
        }
        // Initial check
        handleSidebarCollapseTooltip();
    });
</script>
