<?php

require_once 'db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role'])) {
    http_response_code(401);
    echo json_encode(array('error' => 'Unauthorized'));
    exit;
}

// Get input data
$input = json_decode(file_get_contents('php://input'), true) ?: $_POST;

// Validate input data
if (!isset($input['id']) && !isset($input['name']) && !isset($input['email']) && !isset($input['role'])) {
    http_response_code(400);
    echo json_encode(array('error' => 'Invalid request'));
    exit;
}

// Connect to database
$db = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASSWORD);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// GET all users
if (isset($input['action']) && $input['action'] == 'get_all') {
    $stmt = $db->prepare('SELECT * FROM مستخدمون_راغبون');
    $stmt->execute();
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode($users);
    exit;
}

// GET user by ID
if (isset($input['action']) && $input['action'] == 'get_by_id') {
    $stmt = $db->prepare('SELECT * FROM مستخدمون_راغبون WHERE id = :id');
    $stmt->bindParam(':id', $input['id']);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($user) {
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode($user);
    } else {
        http_response_code(404);
        echo json_encode(array('error' => 'User not found'));
    }
    exit;
}

// POST new user
if (isset($input['action']) && $input['action'] == 'create') {
    // Validate input data
    if (!isset($input['name']) || !isset($input['email']) || !isset($input['role'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid request'));
        exit;
    }
    
    // Sanitize input data
    $name = htmlspecialchars($input['name']);
    $email = htmlspecialchars($input['email']);
    $role = htmlspecialchars($input['role']);
    
    // Insert new user
    $stmt = $db->prepare('INSERT INTO مستخدمون_راغبون (name, email, role) VALUES (:name, :email, :role)');
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':role', $role);
    $stmt->execute();
    
    http_response_code(201);
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'User created successfully'));
    exit;
}

// PUT update user
if (isset($input['action']) && $input['action'] == 'update') {
    // Validate input data
    if (!isset($input['id']) || !isset($input['name']) || !isset($input['email']) || !isset($input['role'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid request'));
        exit;
    }
    
    // Sanitize input data
    $id = htmlspecialchars($input['id']);
    $name = htmlspecialchars($input['name']);
    $email = htmlspecialchars($input['email']);
    $role = htmlspecialchars($input['role']);
    
    // Check if user is admin
    if ($_SESSION['role'] != 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }
    
    // Update user
    $stmt = $db->prepare('UPDATE مستخدمون_راغبون SET name = :name, email = :email, role = :role WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':role', $role);
    $stmt->execute();
    
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'User updated successfully'));
    exit;
}

// DELETE user
if (isset($input['action']) && $input['action'] == 'delete') {
    // Validate input data
    if (!isset($input['id'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid request'));
        exit;
    }
    
    // Check if user is admin
    if ($_SESSION['role'] != 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }
    
    // Delete user
    $stmt = $db->prepare('DELETE FROM مستخدمون_راغبون WHERE id = :id');
    $stmt->bindParam(':id', $input['id']);
    $stmt->execute();
    
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'User deleted successfully'));
    exit;
}

http_response_code(404);
echo json_encode(array('error' => 'Not found'));
exit;