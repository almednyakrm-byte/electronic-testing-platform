<?php

require_once 'db.php';

// Get user data from JSON input or POST data
$input = json_decode(file_get_contents('php://input'), true) ?: $_POST;

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

// Check if user is admin
if (!isset($input['role']) || $input['role'] !== 'admin') {
    http_response_code(403);
    echo json_encode(['error' => 'Forbidden']);
    exit;
}

// Validate and sanitize input data
if (!isset($input['id']) && !isset($input['name']) && !isset($input['email']) && !isset($input['role'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid request']);
    exit;
}

// Prepare SQL statements
$stmt = $pdo->prepare('SELECT * FROM مستخدمون WHERE id = :id');
$stmt->bindParam(':id', $input['id']);
$stmt->execute();

// Handle GET request
if (isset($input['id'])) {
    $user = $stmt->fetch();
    if ($user) {
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode($user);
    } else {
        http_response_code(404);
        echo json_encode(['error' => 'User not found']);
    }
} elseif (isset($input['name']) && isset($input['email']) && isset($input['role'])) {
    // Handle POST request
    $stmt = $pdo->prepare('INSERT INTO مستخدمون (name, email, role) VALUES (:name, :email, :role)');
    $stmt->bindParam(':name', $input['name']);
    $stmt->bindParam(':email', $input['email']);
    $stmt->bindParam(':role', $input['role']);
    $stmt->execute();
    http_response_code(201);
    header('Content-Type: application/json');
    echo json_encode(['message' => 'User created successfully']);
} elseif (isset($input['id']) && isset($input['name']) && isset($input['email']) && isset($input['role'])) {
    // Handle PUT request
    $stmt = $pdo->prepare('UPDATE مستخدمون SET name = :name, email = :email, role = :role WHERE id = :id');
    $stmt->bindParam(':id', $input['id']);
    $stmt->bindParam(':name', $input['name']);
    $stmt->bindParam(':email', $input['email']);
    $stmt->bindParam(':role', $input['role']);
    $stmt->execute();
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(['message' => 'User updated successfully']);
} elseif (isset($input['id'])) {
    // Handle DELETE request
    $stmt = $pdo->prepare('DELETE FROM مستخدمون WHERE id = :id');
    $stmt->bindParam(':id', $input['id']);
    $stmt->execute();
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(['message' => 'User deleted successfully']);
}