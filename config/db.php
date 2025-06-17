<?php
$host = 'localhost';
$db = 'zppsu_evaluation';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

// Connect to MySQL server without DB
$dsn_no_db = "mysql:host=$host;charset=$charset";
try {
    $pdo = new PDO($dsn_no_db, $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `$db` CHARACTER SET $charset COLLATE ${charset}_general_ci");
} catch (\PDOException $e) {
    die("Database creation failed: " . $e->getMessage());
}

// Connect to the database
$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);

    // USERS table
    $pdo->exec("CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(50) UNIQUE NOT NULL,
        password VARCHAR(255) NOT NULL,
        role ENUM('admin','faculty','student') NOT NULL
    )");

    // Insert admin if not exists
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE username = ?");
    $stmt->execute(['admin']);
    if ($stmt->fetchColumn() == 0) {
        $pdo->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)")
            ->execute([
                'admin',
                '$2y$10$6yBqW/xtcKIkLHKRsiY6Tun4wEjLOKZf6MPSNsvHwmlctMLkwc61a',
                'admin'
            ]);
    }

    // DEPARTMENTS table
    $pdo->exec("CREATE TABLE IF NOT EXISTS departments (
        department_id INT AUTO_INCREMENT PRIMARY KEY,
        department_name VARCHAR(100) NOT NULL UNIQUE,
        department_code VARCHAR(50) NOT NULL UNIQUE
    )");

    $departments = [
        ['College of Information and Computing Sciences', 'CICS'],
        ['College of Engineering and Technology', 'CET'],
        ['College of Maritime Education', 'CME'],
        ['College of Teacher Education', 'CTE'],
        ['Institute of Technical Education', 'ITE']
    ];

    foreach ($departments as $dept) {
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM departments WHERE department_code = ?");
        $stmt->execute([$dept[1]]);
        if ($stmt->fetchColumn() == 0) {
            $pdo->prepare("INSERT INTO departments (department_name, department_code) VALUES (?, ?)")
                ->execute($dept);
        }
    }

    // PROGRAMS table
    $pdo->exec("CREATE TABLE IF NOT EXISTS programs (
        program_id INT AUTO_INCREMENT PRIMARY KEY,
        program_name VARCHAR(255) NOT NULL,
        program_code VARCHAR(100) NOT NULL UNIQUE,
        department_id INT NOT NULL,
        FOREIGN KEY (department_id) REFERENCES departments(department_id)
            ON DELETE CASCADE ON UPDATE CASCADE
    )");

    // Map department_code to department_id
    $getDeptId = $pdo->prepare("SELECT department_id FROM departments WHERE department_code = ?");
    $programs = [
        ['Bachelor of Science in Information Technology', 'BS INFOTECH', 'CICS'],
        ['Bachelor of Science in Civil Engineering', 'BS CE', 'CET'],
        ['Bachelor of Science in Computer Science', 'BS COMSCIE', 'CICS'],
        ['Bachelor of Science in Information Systems', 'BS INFOSYS', 'CICS']
    ];

    foreach ($programs as $program) {
        $getDeptId->execute([$program[2]]);
        $deptId = $getDeptId->fetchColumn();
        if ($deptId) {
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM programs WHERE program_code = ?");
            $stmt->execute([$program[1]]);
            if ($stmt->fetchColumn() == 0) {
                $pdo->prepare("INSERT INTO programs (program_name, program_code, department_id) VALUES (?, ?, ?)")
                    ->execute([$program[0], $program[1], $deptId]);
            }
        }
    }

    // SUBJECTS table
    $pdo->exec("CREATE TABLE IF NOT EXISTS subjects (
        subject_id INT AUTO_INCREMENT PRIMARY KEY,
        subject_name VARCHAR(100) NOT NULL,
        subject_code VARCHAR(20) NOT NULL UNIQUE,
        program_id INT,
        department_id INT,
        FOREIGN KEY (program_id) REFERENCES programs(program_id)
            ON DELETE SET NULL ON UPDATE CASCADE,
        FOREIGN KEY (department_id) REFERENCES departments(department_id)
            ON DELETE SET NULL ON UPDATE CASCADE
    )");

    // Map program_code and department_code to their IDs
    $getProgramId = $pdo->prepare("SELECT program_id FROM programs WHERE program_code = ?");
    $subjects = [
        ['INTERNET OF THINGS', 'ITDC 1', 'BS INFOTECH', 'CICS'],
        ['COMPUTER PROGRAMMING 1', 'ITCC 102', 'BS INFOTECH', 'CICS'],
        ['DATA STRUCTURES AND ALGORITHMS', 'ITPC 102', 'BS INFOTECH', 'CICS']
    ];

    foreach ($subjects as $subject) {
        $getProgramId->execute([$subject[2]]);
        $programId = $getProgramId->fetchColumn();

        $getDeptId->execute([$subject[3]]);
        $deptId = $getDeptId->fetchColumn();

        if ($programId && $deptId) {
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM subjects WHERE subject_code = ?");
            $stmt->execute([$subject[1]]);
            if ($stmt->fetchColumn() == 0) {
                $pdo->prepare("INSERT INTO subjects (subject_name, subject_code, program_id, department_id) VALUES (?, ?, ?, ?)")
                    ->execute([$subject[0], $subject[1], $programId, $deptId]);
            }
        }
    }

} catch (\PDOException $e) {
    die("DB setup failed: " . $e->getMessage());
}
