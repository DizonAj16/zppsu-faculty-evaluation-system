<?php
session_start();
require_once dirname(__DIR__) . '/config/db.php';

function signup($username, $password, $role) {
    global $pdo;
    // Validation
    if (empty($username) || empty($password) || empty($role)) {
        return "All fields are required.";
    }
    if (!preg_match('/^[a-zA-Z0-9_]{3,50}$/', $username)) {
        return "Username must be 3-50 characters and contain only letters, numbers, and underscores.";
    }
    if (strlen($password) < 6) {
        return "Password must be at least 6 characters.";
    }
    if (!in_array($role, ['admin', 'faculty', 'student'])) {
        return "Invalid role selected.";
    }
    // Check if username exists
    $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
    $stmt->execute([$username]);
    if ($stmt->fetch()) {
        return "Username already exists.";
    }
    // Insert user
    $hash = password_hash($password, PASSWORD_DEFAULT);
    try {
        $stmt = $pdo->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
        $stmt->execute([$username, $hash, $role]);
        return true;
    } catch (PDOException $e) {
        return "Database error: " . $e->getMessage();
    }
}

function login($username, $password) {
    global $pdo;
    // Validation
    if (empty($username) || empty($password)) {
        return "Username and password are required.";
    }
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    try {
        $stmt->execute([$username]);
        $user = $stmt->fetch();
        if (!$user) {
            return "User not found.";
        }
        if (!password_verify($password, $user['password'])) {
            return "Incorrect password.";
        }
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['role'];
        return true;
    } catch (PDOException $e) {
        return "Database error: " . $e->getMessage();
    }
}

function logout() {
    session_unset();
    session_destroy();
}

function is_logged_in() {
    return isset($_SESSION['user_id']);
}

function get_role() {
    return $_SESSION['role'] ?? null;
}

function require_role($role) {
    if (!is_logged_in() || get_role() !== $role) {
        header('Location: login.php');
        exit();
    }
}