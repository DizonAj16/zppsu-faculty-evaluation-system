<?php
require_once __DIR__ . '../../../includes/auth.php';
require_role('admin');
include __DIR__ . '../../../includes/header-user.php';
?>
<div class="d-flex" id="adminLayout" style="min-height: 100vh;">
    <div id="sidebarContainer">
        <?php include __DIR__ . '../../../includes/sidenav.php'; ?>
    </div>
    <div id="dashboardContainer" class="flex-grow-1">
        <div id="adminPageContent">
            <?php include __DIR__ . '/admin_dashboard.php'; ?>
        </div>
    </div>
</div>

<script>
    
    document.addEventListener('DOMContentLoaded', () => {
        if (added) {
            console.log("Showing updateReq toast");
            Swal.fire({
            toast: true,
            position: 'top-end',
            icon: 'success',
            title: 'Subject added successfully!.',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            customClass: { popup: 'swal2-row-toast' }
            });
            removeUrlParams(['added']);
        }function removeUrlParams(params) {
            const url = new URL(window.location);
            params.forEach(param => url.searchParams.delete(param));
            window.history.replaceState({}, document.title, url.toString());
        }
    });
    // Move pageMap and showComponent OUTSIDE so they can be shared
    const pageMap = {
        dashboard: 'admin_dashboard.php',
        faculty: 'admin_faculty.php',
        programs: 'admin_programs.php',
        subjects: 'admin_subjects.php',
        departments: 'admin_departments.php',
        'year-section': 'admin_year_section.php',
        'faculty-evaluation': 'admin_faculty_evaluation.php',
        settings: 'admin_settings.php'
    };


    function showComponent(page) {
        const content = document.getElementById('adminPageContent');
        if (!content || !pageMap[page]) return;

        const params = new URLSearchParams(window.location.search);
        let fetchUrl = pageMap[page];

        // Preserve limit and status indicators
        const limit = params.get('limit');
        const hasAdded = params.has('added');
        const hasDeleted = params.has('deleted');
        const hasEdited = params.has('edited');

        if (hasAdded || hasDeleted || hasEdited) {
            const queryString = [];
            if (hasAdded) queryString.push('added=1');
            if (hasDeleted) queryString.push('deleted=1');
            if (hasEdited) queryString.push('edited=1');
            fetchUrl += '?' + queryString.join('&');
        }

        history.pushState(null, '', '?page=' + page
            + (hasAdded ? '&added=1' : '')
            + (hasDeleted ? '&deleted=1' : '')
            + (hasEdited ? '&edited=1' : ''));

        fetch(fetchUrl)
            .then(res => res.text())
            .then(html => {
                content.innerHTML = html;

                // Rebind delete modals
                bindDeleteModal('confirmDeleteModalSubject', 'confirmDeleteBtnSubject', 'data-subject-id', '../admin/processes/process_delete_subject.php');
                bindDeleteModal('confirmDeleteModalProgram', 'confirmDeleteBtnProgram', 'data-program-id', '../admin/processes/process_delete_program.php');
                bindDeleteModal('confirmDeleteModalDepartment', 'confirmDeleteBtnDepartment', 'data-department-id', '../admin/processes/process_delete_department.php');
                bindDeleteModal('confirmDeleteModalYearSection', 'confirmDeleteBtnYearSection', 'data-year-section-id', '../admin/processes/process_delete_year_section.php');

                // ✅ Rebind all generic edit modals
                bindEditModals();

                const toastIds = ['addedToast', 'deletedToast', 'editedToast'];

                toastIds.forEach(id => {
                    const toastEl = document.getElementById(id);
                    if (toastEl) {
                        const toast = new bootstrap.Toast(toastEl, {
                            delay: 2000,
                            autohide: true
                        });
                        toast.show();
                    }
                });
            });

        // Clean up the URL after showing toast
        if (hasAdded || hasDeleted || hasEdited) {
            setTimeout(() => {
                params.delete('added');
                params.delete('deleted');
                params.delete('edited');
                const cleanUrl = `${window.location.pathname}?page=${page}`;
                history.replaceState({}, '', cleanUrl);
            }, 3000);
        }
    }

    function bindDeleteModal(modalId, btnId, triggerAttr, targetUrl) {
        const modal = document.getElementById(modalId);
        const confirmBtn = document.getElementById(btnId);

        if (modal && confirmBtn) {
            modal.addEventListener('show.bs.modal', function (event) {
                const trigger = event.relatedTarget;
                const id = trigger.getAttribute(triggerAttr);
                confirmBtn.href = `${targetUrl}?id=${id}`;
            });
        }
    }

    // ✅ Reusable edit modal binding function
    function bindEditModals() {
        document.querySelectorAll('.modal').forEach(modal => {
            modal.addEventListener('show.bs.modal', function (event) {
                const trigger = event.relatedTarget;
                if (!trigger) return;

                // Fill inputs/selects with matching data-edit-field attribute
                modal.querySelectorAll('[data-edit-field]').forEach(input => {
                    const field = input.getAttribute('data-edit-field');
                    const value = trigger.getAttribute(`data-${field}`);
                    if (value !== null) {
                        input.value = value;
                    }
                });
            });
        });
    }




    document.addEventListener('DOMContentLoaded', function () {
        // 📁 Handle sidebar nav clicks
        document.querySelectorAll('#sidebarMenu .nav-link').forEach(link => {
            link.addEventListener('click', function (e) {
                e.preventDefault();
                document.querySelectorAll('#sidebarMenu .nav-link').forEach(l => l.classList.remove('active'));
                this.classList.add('active');
                const page = this.getAttribute('data-page') || 'dashboard';
                showComponent(page);
            });
        });

        // 🚀 Load initial page
        const params = new URLSearchParams(window.location.search);
        const pageParam = params.get('page') || 'dashboard';
        showComponent(pageParam);

        // 🧭 Highlight current nav
        document.querySelectorAll('#sidebarMenu .nav-link').forEach(link => {
            if (link.getAttribute('data-page') === pageParam) {
                link.classList.add('active');
            }
        });
    });


    // ✅ Respond to browser back/forward button
    window.addEventListener('popstate', () => {
        const params = new URLSearchParams(window.location.search);
        const pageParam = params.get('page') || 'dashboard';
        showComponent(pageParam);

        document.querySelectorAll('#sidebarMenu .nav-link').forEach(link => {
            link.classList.toggle('active', link.getAttribute('data-page') === pageParam);
        });
    });

</script>

<?php include __DIR__ . '../../../includes/footer-user.php'; ?>