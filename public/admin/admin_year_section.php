<div class="p-4">
    <h2 class="mb-4">Year and Section Management</h2>

    <!-- Toast Messages -->
    <div class="toast-container position-fixed top-0 end-0 p-3">
        <?php if (isset($_GET['added'])): ?>
            <div id="addedToast" class="toast align-items-center text-bg-success border-0" role="alert">
                <div class="d-flex">
                    <div class="toast-body">Year & Section added successfully!</div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                </div>
            </div>
        <?php endif; ?>
        <?php if (isset($_GET['deleted'])): ?>
            <div id="deletedToast" class="toast align-items-center text-bg-danger border-0" role="alert">
                <div class="d-flex">
                    <div class="toast-body">Year & Section deleted successfully!</div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                </div>
            </div>
        <?php endif; ?>
        <?php if (isset($_GET['edited'])): ?>
            <div id="editedToast" class="toast align-items-center text-bg-primary border-0" role="alert">
                <div class="d-flex">
                    <div class="toast-body">Year & Section edited successfully!</div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="card-title">Year &amp; Section List</h5>
                <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addYearSectionModal">
                    <i class="bi bi-plus-circle"></i> Add Year &amp; Section
                </button>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Year</th>
                            <th>Section</th>
                            <th>Program</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        require_once '../../config/db.php';
                        $stmt = $pdo->query("SELECT ys.*, p.program_name FROM year_sections ys JOIN programs p ON ys.program_id = p.program_id ORDER BY ys.year ASC, ys.section ASC");
                        $rows = $stmt->fetchAll();
                        $count = 1;

                        if ($rows):
                            foreach ($rows as $row): ?>
                                <tr>
                                    <td><?= $count++; ?></td>
                                    <td><?= htmlspecialchars($row['year']) ?></td>
                                    <td><?= htmlspecialchars($row['section']) ?></td>
                                    <td><?= htmlspecialchars($row['program_name']) ?></td>
                                    <td class="text-center">
                                        <button class="btn btn-sm btn-primary me-1" data-bs-toggle="modal"
                                            data-bs-target="#editYearSectionModal"
                                            data-year-section-id="<?= $row['year_section_id'] ?>"
                                            data-year="<?= htmlspecialchars($row['year']) ?>"
                                            data-section="<?= htmlspecialchars($row['section']) ?>"
                                            data-program-id="<?= $row['program_id'] ?>">
                                            <i class="bi bi-pencil"></i> Edit
                                        </button>
                                        <button class="btn btn-sm btn-danger" data-bs-toggle="modal"
                                            data-bs-target="#confirmDeleteModalYearSection"
                                            data-year-section-id="<?= $row['year_section_id'] ?>">
                                            <i class="bi bi-trash"></i> Delete
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach;
                        else: ?>
                            <tr>
                                <td colspan="5" class="text-center text-muted">No Year & Sections found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>


    <!-- Add Modal -->
    <div class="modal fade" id="addYearSectionModal" tabindex="-1">
        <div class="modal-dialog">
            <form method="post" action="../admin/processes/process_add_year_section.php" class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Year & Section</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Year</label>
                        <input type="text" name="year" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Section</label>
                        <input type="text" name="section" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Program</label>
                        <select name="program_id" class="form-select" required>
                            <option value="">-- Select Program --</option>
                            <?php
                            $progStmt = $pdo->query("SELECT * FROM programs ORDER BY program_name ASC");
                            while ($prog = $progStmt->fetch()) {
                                echo "<option value='{$prog['program_id']}'>{$prog['program_name']}</option>";
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Add</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Modal -->
    <div class="modal fade" id="editYearSectionModal" tabindex="-1">
        <div class="modal-dialog">
            <form method="post" action="../admin/processes/process_edit_year_section.php" class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Year & Section</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="year_section_id" data-edit-field="year-section-id">
                    <div class="mb-3">
                        <label class="form-label">Year</label>
                        <input type="text" name="year" class="form-control" data-edit-field="year" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Section</label>
                        <input type="text" name="section" class="form-control" data-edit-field="section" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Program</label>
                        <select name="program_id" class="form-select" data-edit-field="program-id" required>
                            <option value="">-- Select Program --</option>
                            <?php
                            $progStmt = $pdo->query("SELECT * FROM programs ORDER BY program_name ASC");
                            while ($prog = $progStmt->fetch()) {
                                echo "<option value='{$prog['program_id']}'>{$prog['program_name']}</option>";
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Delete Modal -->
    <div class="modal fade" id="confirmDeleteModalYearSection" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirm Deletion</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this Year & Section?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <a href="#" class="btn btn-danger" id="confirmDeleteBtnYearSection">Delete</a>
                </div>
            </div>
        </div>
    </div>
</div>