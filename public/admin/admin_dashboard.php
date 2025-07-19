<?php
require_once __DIR__ . '../../../includes/auth.php';
require_role('admin');
require_once '../../includes/header-user.php';
?>
<div class="d-flex" id="adminLayout" style="min-height: 100vh;">
    <div id="sidebarContainer">
        <?php include '../../includes/sidenav.php'; ?>
    </div>
    <div id="dashboardContainer" class="flex-grow-1">
        <div class="container-fluid p-4">
            <h2 class="mb-4">Admin Dashboard</h2>
            <div class="row g-4">
                <?php
                require_once '../../config/db.php';
                $facultyCount = $pdo->query("SELECT COUNT(*) AS total FROM faculty")->fetch()['total'];
                $subjectCount = $pdo->query("SELECT COUNT(*) AS total FROM subjects")->fetch()['total'];
                $departmentCount = $pdo->query("SELECT COUNT(*) AS total FROM departments")->fetch()['total'];
                $programCount = $pdo->query("SELECT COUNT(*) AS total FROM programs")->fetch()['total'];
                try {
                    $studentCount = $pdo->query("SELECT COUNT(*) AS total FROM students")->fetch()['total'] ?? 0;
                } catch (PDOException $e) {
                    $studentCount = 0; // fallback if table doesn't exist
                }
                try {
                    $evaluationCount = $pdo->query("SELECT COUNT(*) AS total FROM students")->fetch()['total'] ?? 0;
                } catch (PDOException $e) {
                    $evaluationCount = 0; // fallback if table doesn't exist
                }



                // Hardcoded evaluation status for now
                $evaluationStatus = "Open";
                $academicYear = "A.Y. 2025-2026 1st Sem";
                ?>

                <!-- Faculty -->
                <div class="col-md-3">
                    <div class="card shadow-sm border-start border-success border-2">
                        <div class="card-body d-flex align-items-center">
                            <div class="me-3">
                                <i class="bi bi-person-badge-fill text-primary fs-1"></i>
                            </div>
                            <div>
                                <h6 class="card-title mb-0">Faculty</h6>
                                <p class="card-text display-6 fw-bold text-primary mb-0"><?= $facultyCount ?></p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Subjects -->
                <div class="col-md-3">
                    <div class="card shadow-sm border-start border-success border-2">
                        <div class="card-body d-flex align-items-center">
                            <div class="me-3">
                                <i class="bi bi-book-fill text-success fs-1"></i>
                            </div>
                            <div>
                                <h6 class="card-title mb-0">Subjects</h6>
                                <p class="card-text display-6 fw-bold text-success mb-0"><?= $subjectCount ?></p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Departments -->
                <div class="col-md-3">
                    <div class="card shadow-sm border-start border-warning border-2">
                        <div class="card-body d-flex align-items-center">
                            <div class="me-3">
                                <i class="bi bi-building text-warning fs-1"></i>
                            </div>
                            <div>
                                <h6 class="card-title mb-0">Departments</h6>
                                <p class="card-text display-6 fw-bold text-warning mb-0"><?= $departmentCount ?></p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Programs -->
                <div class="col-md-3">
                    <div class="card shadow-sm border-start border-danger border-2">
                        <div class="card-body d-flex align-items-center">
                            <div class="me-3">
                                <i class="bi bi-journals text-danger fs-1"></i>
                            </div>
                            <div>
                                <h6 class="card-title mb-0">Programs</h6>
                                <p class="card-text display-6 fw-bold text-danger mb-0"><?= $programCount ?></p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Students -->
                <div class="col-md-3">
                    <div class="card shadow-sm border-start border-primary border-2">
                        <div class="card-body d-flex align-items-center">
                            <div class="me-3">
                                <i class="bi bi-people-fill text-primary fs-1"></i>
                            </div>
                            <div>
                                <h6 class="card-title mb-0">Students</h6>
                                <p class="card-text display-6 fw-bold text-primary mb-0"><?= $studentCount ?>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Evaluation Status -->
                <div class="col-md-3">
                    <div class="card shadow-sm border-start border-info border-2">
                        <div class="card-body d-flex align-items-center">
                            <div class="me-3">
                                <i class="bi bi-clipboard-check text-info fs-1"></i>
                            </div>
                            <div>
                                <h6 class="card-title mb-0"><?= htmlspecialchars($academicYear) ?> Status</h6>
                                <p class="card-text display-6 fw-bold text-info mb-0">
                                    <?= htmlspecialchars($evaluationStatus) ?>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Total Evaluations Submitted -->
                <div class="col-md-3">
                    <div class="card shadow-sm border-start border-secondary border-2">
                        <div class="card-body d-flex align-items-center">
                            <div class="me-3">
                                <i class="bi bi-check-circle-fill text-secondary fs-1"></i>
                            </div>
                            <div>
                                <h6 class="card-title mb-0">Evaluations Submitted</h6>
                                <p class="card-text display-6 fw-bold text-secondary mb-0">
                                    <?= $evaluationCount ?>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Announcements Section -->
            <div class="mt-5">
                <div class="card shadow-sm">
                    <div class="card-header bg-light">
                        <h5 class="mb-0"><i class="bi bi-megaphone-fill text-warning"></i> Announcements</h5>
                    </div>
                    <div class="card-body">
                        <!-- <?php
                        // $announcements = $pdo->query("SELECT * FROM announcements ORDER BY created_at DESC LIMIT 5")->fetchAll();
                        if ($announcements):
                            foreach ($announcements as $announcement): ?>
                                <div class="border-bottom pb-2 mb-2">
                                    <h6 class="fw-bold"><?= htmlspecialchars($announcement['title']) ?></h6>
                                    <p class="mb-1"><?= nl2br(htmlspecialchars($announcement['content'])) ?></p>
                                    <small class="text-muted">Posted on
                                        <?= date("F d, Y", strtotime($announcement['created_at'])) ?></small>
                                </div>
                            <?php endforeach;
                        else: ?>  -->
                            <p class="text-muted">No announcements available.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
<?php require_once '../../includes/footer-user.php'; ?>