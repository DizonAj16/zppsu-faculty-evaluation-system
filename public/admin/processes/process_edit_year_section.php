<?php
require_once '../../../config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $year_section_id = $_POST['year_section_id'];
    $year = trim($_POST['year']);
    $section = trim($_POST['section']);
    $program_id = $_POST['program_id'];

    if (!empty($year) && !empty($section) && !empty($program_id)) {
        $stmt = $pdo->prepare("UPDATE year_sections SET year = ?, section = ?, program_id = ? WHERE year_section_id = ?");
        $stmt->execute([$year, $section, $program_id, $year_section_id]);

        header("Location: ../admin.php?page=year-section&edited=1");
        exit();
    } else {
        header("Location: ../admin.php?page=year-section&error=empty_fields");
        exit();
    }
} else {
    header("Location: ../admin.php?page=year-section");
    exit();
}
