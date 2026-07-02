<?php

require_once 'db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(array('error' => 'Unauthorized'));
    exit;
}

// Get user role
$user_role = $_SESSION['user_role'];

// Check if user is admin
$is_admin = $user_role == 'admin';

// Get request method
$method = $_SERVER['REQUEST_METHOD'];

// Handle GET request
if ($method == 'GET') {
    // Get user ID from URL parameter
    $user_id = $_GET['id'] ?? null;

    // Check if user ID is provided
    if (!$user_id) {
        http_response_code(400);
        echo json_encode(array('error' => 'User ID is required'));
        exit;
    }

    // Prepare select statement
    $stmt = $pdo->prepare('SELECT * FROM مستخدم WHERE id = :id');
    $stmt->bindParam(':id', $user_id);
    $stmt->execute();

    // Fetch user data
    $user_data = $stmt->fetch();

    // Check if user exists
    if (!$user_data) {
        http_response_code(404);
        echo json_encode(array('error' => 'User not found'));
        exit;
    }

    // Return user data
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode($user_data);
}

// Handle POST request
elseif ($method == 'POST') {
    // Get user data from request body
    $user_data = json_decode(file_get_contents('php://input'), true);

    // Validate user data
    if (!isset($user_data['name']) || !isset($user_data['email']) || !isset($user_data['password'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid user data'));
        exit;
    }

    // Sanitize user data
    $user_data['name'] = trim($user_data['name']);
    $user_data['email'] = trim($user_data['email']);
    $user_data['password'] = password_hash($user_data['password'], PASSWORD_DEFAULT);

    // Prepare insert statement
    $stmt = $pdo->prepare('INSERT INTO مستخدم (name, email, password) VALUES (:name, :email, :password)');
    $stmt->bindParam(':name', $user_data['name']);
    $stmt->bindParam(':email', $user_data['email']);
    $stmt->bindParam(':password', $user_data['password']);
    $stmt->execute();

    // Get inserted user ID
    $user_id = $pdo->lastInsertId();

    // Return user ID
    http_response_code(201);
    header('Content-Type: application/json');
    echo json_encode(array('id' => $user_id));
}

// Handle PUT request
elseif ($method == 'PUT') {
    // Get user ID from URL parameter
    $user_id = $_GET['id'] ?? null;

    // Check if user ID is provided
    if (!$user_id) {
        http_response_code(400);
        echo json_encode(array('error' => 'User ID is required'));
        exit;
    }

    // Get user data from request body
    $user_data = json_decode(file_get_contents('php://input'), true);

    // Validate user data
    if (!isset($user_data['name']) || !isset($user_data['email']) || !isset($user_data['password'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid user data'));
        exit;
    }

    // Sanitize user data
    $user_data['name'] = trim($user_data['name']);
    $user_data['email'] = trim($user_data['email']);
    $user_data['password'] = password_hash($user_data['password'], PASSWORD_DEFAULT);

    // Check if user is admin
    if (!$is_admin) {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }

    // Prepare update statement
    $stmt = $pdo->prepare('UPDATE مستخدم SET name = :name, email = :email, password = :password WHERE id = :id');
    $stmt->bindParam(':id', $user_id);
    $stmt->bindParam(':name', $user_data['name']);
    $stmt->bindParam(':email', $user_data['email']);
    $stmt->bindParam(':password', $user_data['password']);
    $stmt->execute();

    // Check if user was updated
    if ($stmt->rowCount() == 0) {
        http_response_code(404);
        echo json_encode(array('error' => 'User not found'));
        exit;
    }

    // Return success message
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'User updated successfully'));
}

// Handle DELETE request
elseif ($method == 'DELETE') {
    // Get user ID from URL parameter
    $user_id = $_GET['id'] ?? null;

    // Check if user ID is provided
    if (!$user_id) {
        http_response_code(400);
        echo json_encode(array('error' => 'User ID is required'));
        exit;
    }

    // Check if user is admin
    if (!$is_admin) {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }

    // Prepare delete statement
    $stmt = $pdo->prepare('DELETE FROM مستخدم WHERE id = :id');
    $stmt->bindParam(':id', $user_id);
    $stmt->execute();

    // Check if user was deleted
    if ($stmt->rowCount() == 0) {
        http_response_code(404);
        echo json_encode(array('error' => 'User not found'));
        exit;
    }

    // Return success message
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'User deleted successfully'));
}

// Return error message for invalid request method
else {
    http_response_code(405);
    echo json_encode(array('error' => 'Method not allowed'));
}