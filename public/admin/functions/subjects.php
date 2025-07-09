<?php
// api/subjects.php
session_start();
require_once '../../../config/db.php';

header('Content-Type: application/json; charset=utf-8');

try {
    $stmt = $pdo->query("
        SELECT subject_id, subject_name, subject_code
        FROM subjects
        ORDER BY subject_name
    ");
    $subjects = $stmt->fetchAll(PDO::FETCH_ASSOC);
    // echo "heloo po";
    echo json_encode([
        'status'   => 'ok',
        'subjects' => $subjects
    ]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'status'  => 'error',
        'message' => $e->getMessage()
    ]);
}
