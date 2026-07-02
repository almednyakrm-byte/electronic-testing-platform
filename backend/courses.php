<?php
require_once 'db.php';

// Get the request method
$method = $_SERVER['REQUEST_METHOD'];

// Check if the user is logged in
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role'])) {
    http_response_code(401);
    echo json_encode(array('error' => 'Unauthorized'));
    exit;
}

// Check if the user has admin privileges
if ($method === 'PUT' || $method === 'DELETE') {
    if ($_SESSION['role'] !== 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }
}

// Get the request body
$body = json_decode(file_get_contents('php://input'), true);

// Validate the request body
if ($method === 'POST') {
    // Validate course name and description
    if (!isset($body['name']) || !isset($body['description'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid request body'));
        exit;
    }
    $name = filter_var($body['name'], FILTER_SANITIZE_STRING);
    $description = filter_var($body['description'], FILTER_SANITIZE_STRING);
} elseif ($method === 'PUT') {
    // Validate course ID and name
    if (!isset($body['id']) || !isset($body['name'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid request body'));
        exit;
    }
    $id = filter_var($body['id'], FILTER_SANITIZE_NUMBER_INT);
    $name = filter_var($body['name'], FILTER_SANITIZE_STRING);
    $description = filter_var($body['description'], FILTER_SANITIZE_STRING);
}

// Handle the request
if ($method === 'GET') {
    // Get all courses
    $stmt = $pdo->prepare('SELECT * FROM courses');
    $stmt->execute();
    $courses = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($courses);
} elseif ($method === 'POST') {
    // Insert a new course
    $stmt = $pdo->prepare('INSERT INTO courses (name, description) VALUES (:name, :description)');
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':description', $description);
    $stmt->execute();
    http_response_code(201);
    echo json_encode(array('message' => 'Course created successfully'));
} elseif ($method === 'PUT') {
    // Update an existing course
    $stmt = $pdo->prepare('UPDATE courses SET name = :name, description = :description WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':description', $description);
    $stmt->execute();
    http_response_code(200);
    echo json_encode(array('message' => 'Course updated successfully'));
} elseif ($method === 'DELETE') {
    // Delete a course
    $stmt = $pdo->prepare('DELETE FROM courses WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    http_response_code(200);
    echo json_encode(array('message' => 'Course deleted successfully'));
}

http_response_code(200);
header('Content-Type: application/json');