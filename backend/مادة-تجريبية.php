<?php

require_once 'db.php';

// Get user role and authentication status
if (!isset($_SESSION['role']) || !isset($_SESSION['logged_in'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

// Get input data
$input = json_decode(file_get_contents('php://input'), true) ?: $_POST;

// Validate input data
if (!isset($input['id']) && !isset($input['name']) && !isset($input['description'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid request']);
    exit;
}

// Sanitize input data
$input['name'] = trim($input['name'] ?? '');
$input['description'] = trim($input['description'] ?? '');

// Handle CRUD operations
if (isset($input['id'])) {
    // Update operation
    if ($_SESSION['role'] !== 'admin') {
        http_response_code(403);
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }
    
    $stmt = $pdo->prepare('UPDATE مادة_تجريبية SET name = :name, description = :description WHERE id = :id');
    $stmt->bindParam(':id', $input['id']);
    $stmt->bindParam(':name', $input['name']);
    $stmt->bindParam(':description', $input['description']);
    $stmt->execute();
    
    http_response_code(200);
    echo json_encode(['message' => 'Updated successfully']);
} elseif (isset($input['name']) && isset($input['description'])) {
    // Insert operation
    $stmt = $pdo->prepare('INSERT INTO مادة_تجريبية (name, description) VALUES (:name, :description)');
    $stmt->bindParam(':name', $input['name']);
    $stmt->bindParam(':description', $input['description']);
    $stmt->execute();
    
    http_response_code(201);
    echo json_encode(['message' => 'Created successfully']);
} elseif (isset($input['id']) && !isset($input['name']) && !isset($input['description'])) {
    // Delete operation
    if ($_SESSION['role'] !== 'admin') {
        http_response_code(403);
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }
    
    $stmt = $pdo->prepare('DELETE FROM مادة_تجريبية WHERE id = :id');
    $stmt->bindParam(':id', $input['id']);
    $stmt->execute();
    
    http_response_code(200);
    echo json_encode(['message' => 'Deleted successfully']);
} else {
    // Get all or get by id operation
    if (isset($input['id'])) {
        $stmt = $pdo->prepare('SELECT * FROM مادة_تجريبية WHERE id = :id');
        $stmt->bindParam(':id', $input['id']);
    } else {
        $stmt = $pdo->prepare('SELECT * FROM مادة_تجريبية');
    }
    $stmt->execute();
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    http_response_code(200);
    echo json_encode($data);
}

?>