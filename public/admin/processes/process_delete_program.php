<?php
require_once '../../../config/db.php';

if (isset($_GET['id'])) {
    $program_id = $_GET['id'];

    $stmt = $pdo->prepare("DELETE FROM programs WHERE program_id = ?");
    $stmt->execute([$program_id]);

    header("Location: ../admin.php?page=programs&deleted=1");
    exit();
} else {
    header("Location: ../admin.php?page=programs&error=invalid_id");
    exit();
}
