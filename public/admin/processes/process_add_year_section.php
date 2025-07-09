<?php
require_once '../../../config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $year = trim($_POST['year']);
    $section = trim($_POST['section']);
    $program_id = $_POST['program_id'];

    if (!empty($year) && !empty($section) && !empty($program_id)) {
        $stmt = $pdo->prepare("INSERT INTO year_sections (year, section, program_id) VALUES (?, ?, ?)");
        $stmt->execute([$year, $section, $program_id]);

        header("Location: ../admin.php?page=year-section&added=1");
        exit();
    } else {
        header("Location: ../admin.php?page=year-section&error=empty_fields");
        exit();
    }
} else {
    header("Location: ../admin.php?page=year-section");
    exit();
}
