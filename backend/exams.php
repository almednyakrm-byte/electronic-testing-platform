<?php

require_once 'db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role'])) {
    http_response_code(401);
    echo json_encode(array('error' => 'Unauthorized'));
    exit;
}

// Get input data
$input = json_decode(file_get_contents('php://input'), true);

// Handle GET request
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Validate input
    if (!isset($input['id'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid request'));
        exit;
    }

    // Sanitize input
    $id = (int) $input['id'];

    // Check if user is admin
    if ($_SESSION['role'] !== 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }

    // SQL query
    $stmt = $pdo->prepare('SELECT * FROM exams WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->execute();

    // Fetch data
    $data = $stmt->fetch(PDO::FETCH_ASSOC);

    // Output
    if ($data) {
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode($data);
    } else {
        http_response_code(404);
        echo json_encode(array('error' => 'Not found'));
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate input
    if (!isset($input['exam_name']) || !isset($input['exam_date'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid request'));
        exit;
    }

    // Sanitize input
    $exam_name = trim($input['exam_name']);
    $exam_date = trim($input['exam_date']);

    // Check if user is admin
    if ($_SESSION['role'] !== 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }

    // SQL query
    $stmt = $pdo->prepare('INSERT INTO exams (exam_name, exam_date) VALUES (:exam_name, :exam_date)');
    $stmt->bindParam(':exam_name', $exam_name);
    $stmt->bindParam(':exam_date', $exam_date);
    $stmt->execute();

    // Output
    http_response_code(201);
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'Exam created successfully'));
} elseif ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    // Validate input
    if (!isset($input['id']) || !isset($input['exam_name']) || !isset($input['exam_date'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid request'));
        exit;
    }

    // Sanitize input
    $id = (int) $input['id'];
    $exam_name = trim($input['exam_name']);
    $exam_date = trim($input['exam_date']);

    // Check if user is admin
    if ($_SESSION['role'] !== 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }

    // SQL query
    $stmt = $pdo->prepare('UPDATE exams SET exam_name = :exam_name, exam_date = :exam_date WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':exam_name', $exam_name);
    $stmt->bindParam(':exam_date', $exam_date);
    $stmt->execute();

    // Output
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'Exam updated successfully'));
} elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    // Validate input
    if (!isset($input['id'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid request'));
        exit;
    }

    // Sanitize input
    $id = (int) $input['id'];

    // Check if user is admin
    if ($_SESSION['role'] !== 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }

    // SQL query
    $stmt = $pdo->prepare('DELETE FROM exams WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->execute();

    // Output
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'Exam deleted successfully'));
} else {
    http_response_code(405);
    echo json_encode(array('error' => 'Method not allowed'));
}