<?php
require_once '../../../config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $program_id = $_POST['program_id'];
    $program_name = $_POST['program_name'];
    $program_code = $_POST['program_code'];
    $department_id = $_POST['department_id'];

    $stmt = $pdo->prepare("UPDATE programs SET program_name = ?, program_code = ?, department_id = ? WHERE program_id = ?");
    $stmt->execute([$program_name, $program_code, $department_id, $program_id]);

    header("Location: ../admin.php?page=programs&edited=1");
    exit();
}
