<div class="p-4">
    <div class="toast-container position-absolute top-0 end-0 p-3">
        <?php if (isset($_GET['added'])): ?>
            <div id="addedToast" class="toast align-items-center text-white bg-success border-0" role="alert">
                <div class="d-flex">
                    <div class="toast-body">
                        Subject added successfully!
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                </div>
            </div>
        <?php endif; ?>

        <?php if (isset($_GET['deleted'])): ?>
            <div id="deletedToast" class="toast align-items-center text-white bg-danger border-0" role="alert">
                <div class="d-flex">
                    <div class="toast-body">
                        Subject deleted successfully!
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                </div>
            </div>
        <?php endif; ?>

        <?php if (isset($_GET['edited'])): ?>
            <div id="editedToast" class="toast align-items-center text-white bg-info border-0" role="alert">
                <div class="d-flex">
                    <div class="toast-body">
                        Subject updated successfully!
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <h2 class="mb-4">Departments Management</h2>
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <h5 class="card-title">Departments List</h5>
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Department Name</th>
                            <th>Department Code</th>
                            <th>Total Subjects</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php
                        require_once '../../config/db.php';
                        $stmt = $pdo->query("
    SELECT d.*, COUNT(s.subject_id) AS total_subjects
    FROM departments d
    LEFT JOIN subjects s ON d.department_id = s.department_id
    GROUP BY d.department_id
    ORDER BY d.department_name ASC
");
                        $count = 1;
                        $deparments = $stmt->fetchAll();

                        if (count($deparments) > 0):
                            foreach ($deparments as $row):
                                ?>
                                <tr>
                                    <td><?= $count++; ?></td>
                                    <td><?= htmlspecialchars($row['department_name']) ?></td>
                                    <td><?= htmlspecialchars($row['department_code']) ?></td>
                                    <td><?= $row['total_subjects'] ?></td>
                                    <td class="text-center">
                                        <button class="btn btn-sm btn-primary me-1" data-bs-toggle="modal"
                                            data-bs-target="#editDepartmentModal"
                                            data-department-id="<?= $row['department_id'] ?>"
                                            data-department-name="<?= htmlspecialchars($row['department_name']) ?>"
                                            data-department-code="<?= htmlspecialchars($row['department_code']) ?>">
                                            <i class="bi bi-pencil"></i> Edit
                                        </button>
                                        <button class="btn btn-sm btn-danger" data-bs-toggle="modal"
                                            data-bs-target="#confirmDeleteModalDepartment"
                                            data-department-id="<?= $row['department_id'] ?>">
                                            <i class="bi bi-trash"></i> Delete
                                        </button>
                                    </td>
                                </tr>
                                <?php
                            endforeach;
                        else:
                            ?>
                            <tr>
                                <td colspan="4" class="text-center text-muted">No departments found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addDepartmentModal">
        <i class="bi bi-plus-circle"></i> Add Department
    </button>

    <!-- Add Modal -->
    <div class="modal fade" id="addDepartmentModal" tabindex="-1" aria-labelledby="addLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form method="POST" action="../admin/processes/process_add_department.php" class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Department</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="departmentName" class="form-label">Department Name</label>
                        <input type="text" class="form-control" id="departmentName" name="department_name" required>
                    </div>
                    <div class="mb-3">
                        <label for="departmentCode" class="form-label">Department Code</label>
                        <input type="text" class="form-control" id="departmentCode" name="department_code" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Add Department</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Modal -->
    <div class="modal fade" id="editDepartmentModal" tabindex="-1" aria-labelledby="editLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form method="POST" action="../admin/processes/process_edit_department.php" class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Department</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="department_id" id="editDepartmentId" data-edit-field="department-id">
                    <div class="mb-3">
                        <label for="editDepartmentName" class="form-label">Department Name</label>
                        <input type="text" class="form-control" id="editDepartmentName" name="department_name"
                            data-edit-field="department-name" required>
                    </div>
                    <div class="mb-3">
                        <label for="editDepartmentCode" class="form-label">Department Code</label>
                        <input type="text" class="form-control" id="editDepartmentCode" name="department_code"
                            data-edit-field="department-code" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Update Department</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="confirmDeleteModalDepartment" tabindex="-1" aria-labelledby="confirmDeleteModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirm Deletion</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this department?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <a href="#" class="btn btn-danger" id="confirmDeleteBtnDepartment">Delete</a>
                </div>
            </div>
        </div>
    </div>
</div>