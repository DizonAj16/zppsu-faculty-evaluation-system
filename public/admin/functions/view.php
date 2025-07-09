<?php 

declare(strict_types=1);

require_once '../../config/db.php';  
// ================ ALL GET METHOD ===================== //


// ================== FACULTY SIDE ===================== //
function getsubjects(){
    global $pdo;
$stmt = $pdo->query("
    SELECT subject_id, subject_name, subject_code
    FROM subjects
    ORDER BY subject_name
");
$subjects = $stmt->fetchAll(PDO::FETCH_ASSOC);
return ['subjects' => $subjects];
}

function departments(){
    global $pdo;
$stmt = $pdo->query("
    SELECT *
    FROM departments
    ORDER BY department_name 
");
$departments = $stmt->fetchAll(PDO::FETCH_ASSOC);
return ['departments' => $departments];
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


// =============== STUDENT SIDE ======================= //


