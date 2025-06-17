<?php
require_once '../../../config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['department_id']);
    $name = trim($_POST['department_name']);
    $code = trim($_POST['department_code']);

    if ($id && !empty($name) && !empty($code)) {
        $stmt = $pdo->prepare("UPDATE departments SET department_name = ?, department_code = ? WHERE department_id = ?");
        $stmt->execute([$name, $code, $id]);

        header("Location: ../admin.php?page=departments&edited=1");
        exit();
    } else {
        header("Location: ../admin.php?page=departments&error=empty");
        exit();
    }
}
