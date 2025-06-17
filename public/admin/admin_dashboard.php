<?php
require_once '../../config/db.php';

// Get counts
$facultyCount = $pdo->query("SELECT COUNT(*) AS total FROM users WHERE role = 'faculty'")->fetch()['total'];
$subjectCount = $pdo->query("SELECT COUNT(*) AS total FROM subjects")->fetch()['total'];
$departmentCount = $pdo->query("SELECT COUNT(*) AS total FROM departments")->fetch()['total'];
$programCount = $pdo->query("SELECT COUNT(*) AS total FROM programs")->fetch()['total'];
?>

<div class="p-4">
    <h2 class="mb-4">Admin Dashboard</h2>
    <div class="row g-4">


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

    </div>
</div>