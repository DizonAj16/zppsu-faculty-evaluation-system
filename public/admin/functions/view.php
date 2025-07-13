<?php
declare(strict_types=1);

require_once '../../config/db.php';   // adjust if needed

/* =============  JSON ENDPOINT  ==========================
   GET  /public/functions/view.php?id=12
   ------------------------------------------------------ */
if (isset($_GET['id'])) {
    header('Content-Type: application/json');

    $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
    if (!$id) {
        echo json_encode(['error' => 'Invalid ID']); exit;
    }

    $sql = "SELECT faculty.*,
                   subjects.subject_code,
                   subjects.subject_name,
                   departments.department_name
            FROM faculty
            LEFT JOIN subjects    ON faculty.subject_id = subjects.subject_id
            LEFT JOIN departments ON faculty.DEPTid    = departments.department_id
            WHERE faculty.id = :id
            LIMIT 1";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();

    echo json_encode($stmt->fetch(PDO::FETCH_ASSOC) ?: ['error'=>'Not found']);
    exit;
}

/* ---- helper functions (optional) below this line ---- */

function departments(): array
{
    global $pdo;
    $stmt = $pdo->query("SELECT * FROM departments ORDER BY department_name");
    return ['departments' => $stmt->fetchAll(PDO::FETCH_ASSOC)];
}

function getsubjects(): array
{
    global $pdo;
    $stmt = $pdo->query("
        SELECT subject_id, subject_name, subject_code
        FROM subjects
        ORDER BY subject_name
    ");
    return ['subjects' => $stmt->fetchAll(PDO::FETCH_ASSOC)];
}
function faculty(){
        global $pdo;
$stmt = $pdo->query("
    SELECT faculty.faculty_id, subjects.*, faculty.*, departments.department_name
    FROM faculty
    LEFT JOIN subjects ON faculty.subject_id = subjects.subject_id 
    LEFT JOIN departments ON faculty.DEPTid = departments.department_id 
    ORDER BY fulname
");
$faculty = $stmt->fetchAll(PDO::FETCH_ASSOC);
return ['faculty' => $faculty];
}