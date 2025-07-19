<?php
declare(strict_types=1);

require_once '../../config/db.php';

/* ✅ JSON ENDPOINT for single faculty (WITH MULTIPLE SUBJECTS) */
if (isset($_GET['id'])) {
    header('Content-Type: application/json');

    $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
    if (!$id) {
        echo json_encode(['error' => 'Invalid ID']);
        exit;
    }

    // ✅ Get faculty details
$sql = "SELECT 
            f.id,
            f.faculty_id,
            f.fulname,
            f.email,
            f.position,
            f.user_profile,
            f.department_id, 
            d.department_name
        FROM faculty f
        LEFT JOIN departments d ON f.department_id = d.department_id
        WHERE f.id = :id
        LIMIT 1";


    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $faculty = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$faculty) {
        echo json_encode(['error' => 'Not found']);
        exit;
    }

    // ✅ Get all subjects for this faculty
    $sqlSubjects = "SELECT 
                        s.subject_id,
                        s.subject_name,
                        s.subject_code
                    FROM faculty_subjects fs
                    INNER JOIN subjects s ON fs.subject_id = s.subject_id
                    WHERE fs.faculty_id = :faculty_id";
    $stmtSub = $pdo->prepare($sqlSubjects);
    $stmtSub->execute([':faculty_id' => $id]);
    $faculty['subjects'] = $stmtSub->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($faculty);
    exit;
}

/* ✅ Helper functions */
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

function faculty(): array
{
    global $pdo;
    $stmt = $pdo->query("
        SELECT 
            f.id,
            f.faculty_id,
            f.fulname,
            f.email,
            f.position,
            f.user_profile,
            f.department_id,  
            d.department_name
        FROM faculty f
        LEFT JOIN departments d ON f.department_id = d.department_id
        ORDER BY f.fulname
    ");
    $facultyList = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($facultyList as &$fac) {
        $stmtSub = $pdo->prepare("
            SELECT s.subject_id, s.subject_name, s.subject_code
            FROM faculty_subjects fs
            INNER JOIN subjects s ON fs.subject_id = s.subject_id
            WHERE fs.faculty_id = :faculty_id
        ");
        $stmtSub->execute([':faculty_id' => $fac['id']]);
        $fac['subjects'] = $stmtSub->fetchAll(PDO::FETCH_ASSOC);
    }

    return ['faculty' => $facultyList];
}


