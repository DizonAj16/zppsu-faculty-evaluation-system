<?php
require_once '../../../config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['department_name']);
    $code = trim($_POST['department_code']);

    if (!empty($name) && !empty($code)) {
        $stmt = $pdo->prepare("INSERT INTO departments (department_name, department_code) VALUES (?, ?)");
        $stmt->execute([$name, $code]);

        header("Location: ../admin.php?page=departments&added=1");
        exit();
    } else {
        header("Location: ../admin.php?page=departments&error=empty");
        exit();
    }
}
