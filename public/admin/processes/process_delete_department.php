<?php
require_once '../../../config/db.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    $stmt = $pdo->prepare("DELETE FROM departments WHERE department_id = ?");
    $stmt->execute([$id]);

    header("Location: ../admin.php?page=departments&deleted=1");
    exit();
}
