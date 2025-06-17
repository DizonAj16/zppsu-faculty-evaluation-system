<?php
require_once '../../../config/db.php';

if (isset($_GET['id'])) {
    $subject_id = intval($_GET['id']);

    try {
        $stmt = $pdo->prepare("DELETE FROM subjects WHERE subject_id = ?");
        $stmt->execute([$subject_id]);

        header("Location: ../admin.php?page=subjects&deleted=1");
        exit;
    } catch (PDOException $e) {
        echo "Error deleting subject: " . $e->getMessage();
    }
} else {
    header("Location: ../admin.php");
    exit;
}
