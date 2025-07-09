<?php
require_once '../../../config/db.php';

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $year_section_id = (int)$_GET['id'];

    $stmt = $pdo->prepare("DELETE FROM year_sections WHERE year_section_id = ?");
    $stmt->execute([$year_section_id]);

    header("Location: ../admin.php?page=year-section&deleted=1");
    exit();
} else {
    header("Location: ../admin.php?page=year-section&error=invalid_id");
    exit();
}
