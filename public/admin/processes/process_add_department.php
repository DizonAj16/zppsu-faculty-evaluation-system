<?php
require_once '../../../config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['department_name']);
    $code = trim($_POST['department_code']);
    
   $stmt = $pdo->prepare("SELECT * FROM departments WHERE department_code = :department_code");
    $stmt->execute(['department_code' => $code]);
    $existingCode = $stmt->fetch(PDO::FETCH_ASSOC);

    if($code === $existingCode['department_code']) {
        header("Location: ../admin.php?page=departments&code=exist");
        exit();
    }else{
        if (!empty($name) && !empty($code)) {
            $stmt = $pdo->prepare("INSERT INTO departments (department_name, department_code) VALUES (?, ?)");
            $stmt->execute([$name, $code]);

            header("Location: ../admin.php?page=departments&added=1");
            exit();
        } else {
            header("Location: ../admin.php?page=departments&error=empty");
            exit();
        }
    }
    

}
