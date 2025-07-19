<?php
require_once '../../../config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Determine the action (add or edit)
    $action = isset($_POST['action']) ? $_POST['action'] : '';

    // ✅ ADD DEPARTMENT
    if ($action === 'add') {
        $name = trim($_POST['department_name']);
        $code = trim($_POST['department_code']);

        // Check if code already exists
        $stmt = $pdo->prepare("SELECT department_code FROM departments WHERE department_code = :department_code");
        $stmt->execute(['department_code' => $code]);
        $existingCode = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($existingCode) {
            header("Location: ../admin_departments.php?code=exist");
            exit();
        }

        if (!empty($name) && !empty($code)) {
            $stmt = $pdo->prepare("INSERT INTO departments (department_name, department_code) VALUES (?, ?)");
            $stmt->execute([$name, $code]);

            header("Location: ../admin_departments.php?added=1");
            exit();
        } else {
            header("Location: ../admin_departments.php?error=empty");
            exit();
        }
    }

    // ✅ EDIT DEPARTMENT
    if ($action === 'edit') {
        $id = intval($_POST['department_id']);
        $name = trim($_POST['department_name']);
        $code = trim($_POST['department_code']);

        if ($id && !empty($name) && !empty($code)) {
            $stmt = $pdo->prepare("UPDATE departments SET department_name = ?, department_code = ? WHERE department_id = ?");
            $stmt->execute([$name, $code, $id]);

            header("Location: ../admin_departments.php?edited=1");
            exit();
        } else {
            header("Location: ../admin_departments.php?error=empty");
            exit();
        }
    }
}

// ✅ DELETE DEPARTMENT (GET METHOD)
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);

    if ($id) {
        $stmt = $pdo->prepare("DELETE FROM departments WHERE department_id = ?");
        $stmt->execute([$id]);

        header("Location: ../admin_departments.php?deleted=1");
        exit();
    }
}
