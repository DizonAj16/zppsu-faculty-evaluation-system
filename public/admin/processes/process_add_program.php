<?php
require_once '../../../config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $program_name = trim($_POST['program_name']);
    $program_code = trim($_POST['program_code']);
    $department_id = $_POST['department_id'];

    if (!empty($program_name) && !empty($program_code) && !empty($department_id)) {
        $stmt = $pdo->prepare("INSERT INTO programs (program_name, program_code, department_id) VALUES (?, ?, ?)");
        $stmt->execute([$program_name, $program_code, $department_id]);

        header("Location: ../admin.php?page=programs&added=1");
        exit();
    } else {
        header("Location: ../admin.php?page=programs&error=empty_fields");
        exit();
    }
} else {
    header("Location: ../admin.php?page=programs");
    exit();
}
