<div class="p-4">
    <div class="toast-container position-absolute top-0 end-0 p-3">
        <?php 
        // if (isset($_GET['added'])):
         ?>
            <!-- <div id="addedToast" class="toast align-items-center text-white bg-success border-0" role="alert">
                <div class="d-flex">
                    <div class="toast-body">
                        Subject added successfully!
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                </div>
            </div> -->
        <?php 
    // endif;
     ?>

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




    <h2 class="mb-4">Subjects Management</h2>
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <h5 class="card-title">Subjects List</h5>
            <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Subject Name</th>
                            <th>Code</th>
                            <th>Program</th>
                            <th>Department</th>
                            <th>Subject type</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php
                        require_once '../../config/db.php';

                        $stmt = $pdo->query("
        SELECT 
            s.subject_id, 
            s.subject_name, 
            s.subject_code, 
            s.program_id,             
            s.department_id,
            s.subject_type,          
            d.department_name,
            d.department_code, 
            p.program_name
        FROM subjects s
        LEFT JOIN departments d ON s.department_id = d.department_id
        LEFT JOIN programs p ON s.program_id = p.program_id
        ORDER BY s.subject_name ASC
    ");

                        $subjects = $stmt->fetchAll();
                        $count = 1;

                        if (count($subjects) > 0):
                            foreach ($subjects as $row):
                                ?>
                                <tr>
                                    <td><?= $count++; ?></td>
                                    <td><?= htmlspecialchars($row['subject_name']) ?></td>
                                    <td><?= htmlspecialchars($row['subject_code']) ?></td>
                                    <td><?= htmlspecialchars($row['program_name']) ?></td>
                                    <td><?= htmlspecialchars($row['department_code']) ?></td>
                                    <td><?= htmlspecialchars($row['subject_type']) ?></td>
                                    <td class="text-center">
                                        <button class="btn btn-sm btn-primary me-1" data-bs-toggle="modal"
                                            data-bs-target="#editSubjectModal" data-subject-id="<?= $row['subject_id'] ?>"
                                            data-subject-name="<?= htmlspecialchars($row['subject_name']) ?>"
                                            data-subject-code="<?= htmlspecialchars($row['subject_code']) ?>"
                                            data-program-id="<?= $row['program_id'] ?>"
                                            data-department-id="<?= $row['department_id'] ?>"
                                            data-subject-type="<?= htmlspecialchars($row['subject_type']) ?>">
                                            <i class="bi bi-pencil"></i> Edit
                                        </button>

                                        <a href="#" class="btn btn-sm btn-danger" data-bs-toggle="modal"
                                            data-bs-target="#confirmDeleteModalSubject"
                                            data-subject-id="<?= $row['subject_id'] ?>">
                                            <i class="bi bi-trash"></i> Delete
                                        </a>
                                    </td>
                                </tr>
                                <?php
                            endforeach;
                        else:
                            ?>
                            <tr>
                                <td colspan="6" class="text-center text-muted">No subjects found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>

                </table>
            </div>
        </div>
    </div>
    <div>
        <!-- Button trigger modal -->
        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addSubjectModal"><i
                class="bi bi-plus-circle"></i>
            Add Subject
        </button>

        <!-- add Modal -->
        <div class="modal fade" id="addSubjectModal" tabindex="-1" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable">
                <form method="post" action="../admin/processes/process_add_subject.php" class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add Subject</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <!-- Subject Name -->
                        <div class="mb-3">
                            <label for="subjectName" class="form-label">Subject Name</label>
                            <input type="text" class="form-control" id="subjectName" name="subject_name" required>
                        </div>

                        <!-- Subject Code -->
                        <div class="mb-3">
                            <label for="subjectCode" class="form-label">Subject Code</label>
                            <input type="text" class="form-control" id="subjectCode" name="subject_code" required>
                        </div>

                        <!-- Program Dropdown -->
                        <div class="mb-3">
                            <label for="program" class="form-label">Program</label>
                            <select id="program" name="program_id" class="form-select" required>
                                <option value="">-- Select Program --</option>
                                <?php
                                require_once '../../config/db.php';
                                $stmt = $pdo->query("SELECT program_id, program_name FROM programs ORDER BY program_name ASC");
                                while ($row = $stmt->fetch()) {
                                    echo "<option value='{$row['program_id']}'>{$row['program_name']}</option>";
                                }
                                ?>
                            </select>
                        </div>

                        <!-- Department Dropdown -->
                        <div class="mb-3">
                            <label for="department" class="form-label">Department</label>
                            <select id="department" name="department_id" class="form-select" required>
                                <option value="">-- Select Department --</option>
                                <?php
                                $stmt = $pdo->query("SELECT department_id, department_name FROM departments ORDER BY department_name ASC");
                                while ($row = $stmt->fetch()) {
                                    echo "<option value='{$row['department_id']}'>{$row['department_name']}</option>";
                                }
                                ?>
                            </select>
                        </div>

                        <!-- Subject Type -->
                        <div class="mb-3">
                            <label for="subjectType" class="form-label">Subject Type</label>
                            <select id="subjectType" name="subject_type" class="form-select" required>
                                <option value="">-- Select Subject Type --</option>
                                <?php
                                // Fetch unique subject types only
                                $stmt = $pdo->query("SELECT DISTINCT subject_type FROM subjects ORDER BY subject_type ASC");
                                while ($row = $stmt->fetch()) {
                                    echo "<option value='" . htmlspecialchars($row['subject_type']) . "'>" . htmlspecialchars($row['subject_type']) . "</option>";
                                }
                                ?>
                            </select>
                        </div>

                        <div class="modal-footer">
                            <button type="submit" class="btn btn-success">Add Subject</button>
                        </div>
                </form>
            </div>
        </div>


    </div>
    <!-- Confirm Delete Modal -->
    <div class="modal fade" id="confirmDeleteModalSubject" tabindex="-1" aria-labelledby="confirmDeleteModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmDeleteModalLabel">Confirm Deletion</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this subject?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <a href="#" class="btn btn-danger" id="confirmDeleteBtnSubject">Delete</a>
                </div>
            </div>
        </div>
    </div>


    <!-- Edit Modal -->
    <div class="modal fade" id="editSubjectModal" tabindex="-1" aria-labelledby="editSubjectLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable">
            <form method="post" action="../admin/processes/process_edit_subject.php" class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Subject</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="subject_id" data-edit-field="subject-id" />

                    <div class="mb-3">
                        <label for="editSubjectName" class="form-label">Subject Name</label>
                        <input type="text" class="form-control" name="subject_name" data-edit-field="subject-name"
                            required>
                    </div>

                    <div class="mb-3">
                        <label for="editSubjectCode" class="form-label">Subject Code</label>
                        <input type="text" class="form-control" name="subject_code" data-edit-field="subject-code"
                            required>
                    </div>

                    <div class="mb-3">
                        <label for="editProgram" class="form-label">Program</label>
                        <select class="form-select" name="program_id" data-edit-field="program-id" required>
                            <option value="">-- Select Program --</option>
                            <?php
                            $stmt = $pdo->query("SELECT program_id, program_name FROM programs ORDER BY program_name ASC");
                            while ($row = $stmt->fetch()) {
                                echo "<option value='{$row['program_id']}'>{$row['program_name']}</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="editDepartment" class="form-label">Department</label>
                        <select class="form-select" name="department_id" data-edit-field="department-id" required>
                            <option value="">-- Select Department --</option>
                            <?php
                            $stmt = $pdo->query("SELECT department_id, department_name FROM departments ORDER BY department_name ASC");
                            while ($row = $stmt->fetch()) {
                                echo "<option value='{$row['department_id']}'>{$row['department_name']}</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="editSubjectType" class="form-label">Subject Type</label>
                        <select class="form-select" name="subject_type" data-edit-field="subject-type" required>
                            <option value="">-- Select Subject Type --</option>
                            <?php
                            // Fetch unique subject types only
                            $stmt = $pdo->query("SELECT DISTINCT subject_type FROM subjects ORDER BY subject_type ASC");
                            while ($row = $stmt->fetch()) {
                                echo "<option value='" . htmlspecialchars($row['subject_type']) . "'>" . htmlspecialchars($row['subject_type']) . "</option>";
                            }
                            ?>
                        </select>
                    </div>


                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Update Subject</button>
                    </div>
            </form>
        </div>
    </div>

</div>