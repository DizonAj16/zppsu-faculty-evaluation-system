<?php
require_once '../../../config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['subject_name'];
    $code = $_POST['subject_code'];
    $dept_id = $_POST['department_id'];
    $program_id = $_POST['program_id'];
    $sub_type = $_POST['subject_type'] ?? 'Major';

    try {
        $stmt = $pdo->prepare("INSERT INTO subjects (subject_name, subject_code, program_id, department_id, subject_type) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$name, $code, $program_id, $dept_id, $sub_type]);

        header("Location: ../admin.php?page=subjects&added=1");
        exit;
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    header("Location: ../admin.php");
    exit;
}
