<?php
require_once 'db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_role'])) {
    http_response_code(401);
    echo json_encode(array('error' => 'Unauthorized'));
    exit;
}

// Get input data
$input = json_decode(file_get_contents('php://input'), true);

// Define routes
$routes = array(
    '/exams' => array('GET', 'POST'),
    '/exams/:id' => array('GET', 'PUT', 'DELETE')
);

// Get route and method
$route = $_SERVER['REQUEST_URI'];
$method = $_SERVER['REQUEST_METHOD'];

// Check if route and method are valid
if (!isset($routes[$route]) || !in_array($method, $routes[$route])) {
    http_response_code(405);
    echo json_encode(array('error' => 'Method Not Allowed'));
    exit;
}

// Validate and sanitize input data
if ($method == 'POST') {
    $required_fields = array('exam_name', 'exam_date', 'exam_time');
    foreach ($required_fields as $field) {
        if (!isset($input[$field])) {
            http_response_code(400);
            echo json_encode(array('error' => 'Invalid request'));
            exit;
        }
        $input[$field] = filter_var($input[$field], FILTER_SANITIZE_STRING);
    }
}

// Handle GET request
if ($method == 'GET') {
    if (strpos($route, '/:id') !== false) {
        $id = explode('/', $route)[count(explode('/', $route)) - 1];
        $stmt = $pdo->prepare('SELECT * FROM exams WHERE id = :id');
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $exam = $stmt->fetch();
        if ($exam) {
            http_response_code(200);
            header('Content-Type: application/json');
            echo json_encode($exam);
        } else {
            http_response_code(404);
            echo json_encode(array('error' => 'Exam not found'));
        }
    } else {
        $stmt = $pdo->prepare('SELECT * FROM exams');
        $stmt->execute();
        $exams = $stmt->fetchAll();
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode($exams);
    }
}

// Handle POST request
if ($method == 'POST') {
    $required_fields = array('exam_name', 'exam_date', 'exam_time');
    foreach ($required_fields as $field) {
        if (!isset($input[$field])) {
            http_response_code(400);
            echo json_encode(array('error' => 'Invalid request'));
            exit;
        }
        $input[$field] = filter_var($input[$field], FILTER_SANITIZE_STRING);
    }
    $stmt = $pdo->prepare('INSERT INTO exams (exam_name, exam_date, exam_time) VALUES (:exam_name, :exam_date, :exam_time)');
    $stmt->bindParam(':exam_name', $input['exam_name']);
    $stmt->bindParam(':exam_date', $input['exam_date']);
    $stmt->bindParam(':exam_time', $input['exam_time']);
    $stmt->execute();
    http_response_code(201);
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'Exam created successfully'));
}

// Handle PUT request
if ($method == 'PUT') {
    if (!isset($input['id'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid request'));
        exit;
    }
    $id = $input['id'];
    $required_fields = array('exam_name', 'exam_date', 'exam_time');
    foreach ($required_fields as $field) {
        if (!isset($input[$field])) {
            http_response_code(400);
            echo json_encode(array('error' => 'Invalid request'));
            exit;
        }
        $input[$field] = filter_var($input[$field], FILTER_SANITIZE_STRING);
    }
    if ($_SESSION['user_role'] != 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }
    $stmt = $pdo->prepare('UPDATE exams SET exam_name = :exam_name, exam_date = :exam_date, exam_time = :exam_time WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':exam_name', $input['exam_name']);
    $stmt->bindParam(':exam_date', $input['exam_date']);
    $stmt->bindParam(':exam_time', $input['exam_time']);
    $stmt->execute();
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'Exam updated successfully'));
}

// Handle DELETE request
if ($method == 'DELETE') {
    if (!isset($input['id'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid request'));
        exit;
    }
    $id = $input['id'];
    if ($_SESSION['user_role'] != 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }
    $stmt = $pdo->prepare('DELETE FROM exams WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'Exam deleted successfully'));
}
?>