<?php
require_once '../../../config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $subject_id = $_POST['subject_id'];
    $subject_name = $_POST['subject_name'];
    $subject_code = $_POST['subject_code'];
    $program_id = $_POST['program_id'];
    $department_id = $_POST['department_id'];
    $sub_type = $_POST['subject_type'] ?? 'Major';


    $stmt = $pdo->prepare("UPDATE subjects SET subject_name = ?, subject_code = ?, program_id = ?, department_id = ?, subject_type = ? WHERE subject_id = ?");
    $stmt->execute([$subject_name, $subject_code, $program_id, $department_id, $sub_type, $subject_id]);


    header("Location: ../admin.php?page=subjects&edited=1");
    exit;
}
?>