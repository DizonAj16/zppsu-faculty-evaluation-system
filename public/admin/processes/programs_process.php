<?php
require_once '../../../config/db.php';

$redirect = "../admin_programs.php";

// ✅ Handle POST (Add & Edit)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];

    if ($action === 'add') {
        // --- ADD PROGRAM ---
        $program_name = trim($_POST['program_name']);
        $program_code = trim($_POST['program_code']);
        $department_id = $_POST['department_id'];

        if (!empty($program_name) && !empty($program_code) && !empty($department_id)) {
            $stmt = $pdo->prepare("INSERT INTO programs (program_name, program_code, department_id) VALUES (?, ?, ?)");
            $stmt->execute([$program_name, $program_code, $department_id]);
            header("Location: $redirect?added=1");
            exit();
        } else {
            header("Location: $redirect?error=empty_fields");
            exit();
        }
    }

    if ($action === 'edit') {
        // --- EDIT PROGRAM ---
        $program_id = $_POST['program_id'];
        $program_name = trim($_POST['program_name']);
        $program_code = trim($_POST['program_code']);
        $department_id = $_POST['department_id'];

        if (!empty($program_id) && !empty($program_name) && !empty($program_code) && !empty($department_id)) {
            $stmt = $pdo->prepare("UPDATE programs 
                                   SET program_name = ?, program_code = ?, department_id = ? 
                                   WHERE program_id = ?");
            $stmt->execute([$program_name, $program_code, $department_id, $program_id]);
            header("Location: $redirect?edited=1");
            exit();
        } else {
            header("Location: $redirect?error=empty_fields");
            exit();
        }
    }
}

// ✅ Handle GET (Delete)
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'delete') {
    if (!empty($_GET['id'])) {
        $program_id = $_GET['id'];
        $stmt = $pdo->prepare("DELETE FROM programs WHERE program_id = ?");
        $stmt->execute([$program_id]);
        header("Location: $redirect?deleted=1");
        exit();
    } else {
        header("Location: $redirect?error=invalid_id");
        exit();
    }
}

// ✅ Default fallback
header("Location: $redirect");
exit();
