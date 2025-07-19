<?php
require_once '../../../config/db.php';

// ✅ Handle Add Year & Section
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add') {
    $year = trim($_POST['year']);
    $section = trim($_POST['section']);
    $program_id = $_POST['program_id'];

    if (!empty($year) && !empty($section) && !empty($program_id)) {
        $stmt = $pdo->prepare("INSERT INTO year_sections (year, section, program_id) VALUES (?, ?, ?)");
        $stmt->execute([$year, $section, $program_id]);

        header("Location: ../admin_year_section.php?added=1");
        exit();
    } else {
        header("Location: ../admin_year_section.php?error=empty_fields");
        exit();
    }
}

// ✅ Handle Edit Year & Section
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'edit') {
    $year_section_id = $_POST['year_section_id'];
    $year = trim($_POST['year']);
    $section = trim($_POST['section']);
    $program_id = $_POST['program_id'];

    if (!empty($year) && !empty($section) && !empty($program_id) && !empty($year_section_id)) {
        $stmt = $pdo->prepare("UPDATE year_sections SET year = ?, section = ?, program_id = ? WHERE year_section_id = ?");
        $stmt->execute([$year, $section, $program_id, $year_section_id]);

        header("Location: ../admin_year_section.php?edited=1");
        exit();
    } else {
        header("Location: ../admin_year_section.php?error=empty_fields");
        exit();
    }
}

// ✅ Handle Delete Year & Section
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id']) && is_numeric($_GET['id'])) {
    $year_section_id = (int)$_GET['id'];

    $stmt = $pdo->prepare("DELETE FROM year_sections WHERE year_section_id = ?");
    $stmt->execute([$year_section_id]);

    header("Location: ../admin_year_section.php?deleted=1");
    exit();
}

// ✅ Redirect if no valid action
header("Location: ../admin_year_section.php");
exit();
