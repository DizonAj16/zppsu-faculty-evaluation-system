<?php
require_once '../../../config/db.php';

$redirect = "../admin_subjects.php";

try {
    // ✅ 1. Handle ADD & EDIT (POST request)
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
        $action = $_POST['action'];

        if ($action === 'add') {
            // --- ADD SUBJECT ---
            $name = trim($_POST['subject_name']);
            $code = trim($_POST['subject_code']);
            $dept_id = $_POST['department_id'];
            $program_id = $_POST['program_id'];
            $sub_type = $_POST['subject_type'] ?? 'Major';

            if (!empty($name) && !empty($code) && !empty($dept_id) && !empty($program_id)) {
                $stmt = $pdo->prepare("INSERT INTO subjects (subject_name, subject_code, program_id, department_id, subject_type) 
                                       VALUES (?, ?, ?, ?, ?)");
                $stmt->execute([$name, $code, $program_id, $dept_id, $sub_type]);

                header("Location: {$redirect}?added=1");
                exit;
            } else {
                header("Location: {$redirect}?error=empty_fields");
                exit;
            }
        }

        if ($action === 'edit') {
            // --- EDIT SUBJECT ---
            $subject_id = $_POST['subject_id'];
            $subject_name = trim($_POST['subject_name']);
            $subject_code = trim($_POST['subject_code']);
            $program_id = $_POST['program_id'];
            $department_id = $_POST['department_id'];
            $sub_type = $_POST['subject_type'] ?? 'Major';

            if (!empty($subject_id) && !empty($subject_name) && !empty($subject_code)) {
                $stmt = $pdo->prepare("UPDATE subjects 
                                       SET subject_name = ?, subject_code = ?, program_id = ?, department_id = ?, subject_type = ? 
                                       WHERE subject_id = ?");
                $stmt->execute([$subject_name, $subject_code, $program_id, $department_id, $sub_type, $subject_id]);

                header("Location: {$redirect}?edited=1");
                exit;
            } else {
                header("Location: {$redirect}?error=empty_fields");
                exit;
            }
        }
    }

    // ✅ 2. Handle DELETE (GET request)
    if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['delete'])) {
        $subject_id = intval($_GET['delete']);
        if ($subject_id > 0) {
            $stmt = $pdo->prepare("DELETE FROM subjects WHERE subject_id = ?");
            $stmt->execute([$subject_id]);

            header("Location: {$redirect}?deleted=1");
            exit;
        } else {
            header("Location: {$redirect}?error=invalid_id");
            exit;
        }
    }

    // ✅ Default redirect if no action is specified
    header("Location: {$redirect}");
    exit;

} catch (PDOException $e) {
    echo "Database Error: " . $e->getMessage();
}
