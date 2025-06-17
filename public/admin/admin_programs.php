<div class="p-4">
    <?php if (isset($_GET['added'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            Program added successfully!
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if (isset($_GET['deleted'])): ?>
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            Program deleted successfully!
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if (isset($_GET['edited'])): ?>
        <div class="alert alert-info alert-dismissible fade show" role="alert">
            Program updated successfully!
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>



    <h2 class="mb-4">Programs Management</h2>
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <h5 class="card-title">Programs List</h5>
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Program Name</th>
                            <th>Code</th>
                            <th>Department</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        require_once '../../config/db.php';

                        $stmt = $pdo->query("
    SELECT 
        p.program_id, 
        p.program_name, 
        p.program_code, 
        p.department_id,       
        d.department_code 
    FROM programs p
    LEFT JOIN departments d ON p.department_id = d.department_id
    ORDER BY p.program_name ASC
");
                        $progams = $stmt->fetchAll();
                        $count = 1;

                        if (count($progams) > 0):
                            foreach ($progams as $row):
                                ?>
                            <tr>
                                <td><?= $count++; ?></td>
                                <td><?= htmlspecialchars($row['program_name']) ?></td>
                                <td><?= htmlspecialchars($row['program_code']) ?></td>
                                <td><?= htmlspecialchars($row['department_code']) ?></td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-primary me-1" data-bs-toggle="modal"
                                        data-bs-target="#editProgramModal" data-program-id="<?= $row['program_id'] ?>"
                                        data-program-name="<?= htmlspecialchars($row['program_name']) ?>"
                                        data-program-code="<?= htmlspecialchars($row['program_code']) ?>"
                                        data-department-id="<?= $row['department_id'] ?>">
                                        <i class="bi bi-pencil"></i> Edit
                                    </button>
                                    <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal"
                                        data-bs-target="#confirmDeleteModalProgram"
                                        data-program-id="<?= $row['program_id'] ?>">
                                        <i class="bi bi-trash"></i> Delete
                                    </button>
                                </td>
                            </tr>
                        <?php
                            endforeach;
                        else:
                            ?>
                            <tr>
                                <td colspan="6" class="text-center text-muted">No Programs found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Add Program Button & Modal -->
    <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addProgramModal">
        <i class="bi bi-plus-circle"></i> Add Program
    </button>

    <div class="modal fade" id="addProgramModal" tabindex="-1" aria-labelledby="addProgramLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable">
            <form method="post" action="../admin/processes/process_add_program.php" class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Program</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Program Name -->
                    <div class="mb-3">
                        <label for="programName" class="form-label">Program Name</label>
                        <input type="text" class="form-control" id="programName" name="program_name" required>
                    </div>

                    <!-- Program Code -->
                    <div class="mb-3">
                        <label for="programCode" class="form-label">Program Code</label>
                        <input type="text" class="form-control" id="programCode" name="program_code" required>
                    </div>

                    <!-- Department Dropdown -->
                    <div class="mb-3">
                        <label for="department" class="form-label">Department</label>
                        <select id="department" name="department_id" class="form-select" required>
                            <option value="">-- Select Department --</option>
                            <?php
                            $stmt = $pdo->query("SELECT * FROM departments ORDER BY department_name ASC");
                            while ($row = $stmt->fetch()) {
                                echo "<option value='{$row['department_id']}'>{$row['department_name']}</option>";
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Add Program</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Confirm Delete Modal -->
    <div class="modal fade" id="confirmDeleteModalProgram" tabindex="-1" aria-labelledby="confirmDeleteModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirm Deletion</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this program?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <a href="#" class="btn btn-danger" id="confirmDeleteBtnProgram">Delete</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Program Modal -->
    <div class="modal fade" id="editProgramModal" tabindex="-1" aria-labelledby="editProgramLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable">
            <form method="post" action="../admin/processes/process_edit_program.php" class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Program</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="program_id" data-edit-field="program-id">

                    <div class="mb-3">
                        <label for="editProgramName" class="form-label">Program Name</label>
                        <input type="text" class="form-control" name="program_name" data-edit-field="program-name"
                            required>
                    </div>

                    <div class="mb-3">
                        <label for="editProgramCode" class="form-label">Program Code</label>
                        <input type="text" class="form-control" name="program_code" data-edit-field="program-code"
                            required>
                    </div>

                    <div class="mb-3">
                        <label for="editDepartment" class="form-label">Department</label>
                        <select name="department_id" class="form-select" data-edit-field="department-id" required>
                            <option value="">-- Select Department --</option>
                            <?php
                            $stmt = $pdo->query("SELECT * FROM departments ORDER BY department_name ASC");
                            while ($row = $stmt->fetch()) {
                                echo "<option value='{$row['department_id']}'>{$row['department_name']}</option>";
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Update Program</button>
                </div>
            </form>
        </div>
    </div>
</div>