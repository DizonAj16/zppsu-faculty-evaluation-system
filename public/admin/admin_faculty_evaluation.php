<?php 
require_once __DIR__ . '../../../includes/auth.php';
require_role('admin');
require_once '../../includes/header-user.php'; ?>
<div class="d-flex" id="adminLayout" style="min-height: 100vh;">
    <div id="sidebarContainer">
        <?php include '../../includes/sidenav.php'; ?>
    </div>
    <div id="dashboardContainer" class="flex-grow-1">
        <div class="container-fluid p-4">
            <p class="text-end text-danger"><b>This Page is under construction, No functions yet.</b></b>
            <h2 class="mb-4">Faculty Evaluation Management</h2>
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h5 class="card-title">Evaluation Records</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Faculty Name</th>
                                    <th>Subject</th>
                                    <th>Year &amp; Section</th>
                                    <th>Score</th>
                                    <th>Date</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Example row -->
                                <tr>
                                    <td>1</td>
                                    <td>Juan Dela Cruz</td>
                                    <td>Programming 1</td>
                                    <td>1st Year - A</td>
                                    <td>95%</td>
                                    <td>2024-04-27</td>
                                    <td class="text-center">
                                        <button class="btn btn-sm btn-primary me-1"><i class="bi bi-eye"></i> View</button>
                                        <button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i> Delete</button>
                                    </td>
                                </tr>
                                <!-- Add more rows as needed -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once '../../includes/footer-user.php'; ?>