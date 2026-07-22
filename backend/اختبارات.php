<?php
require_once 'db.php';

// Check if user is logged in
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    http_response_code(401);
    echo json_encode(array('error' => 'Unauthorized'));
    exit;
}

// Get input data
$input = json_decode(file_get_contents('php://input'), true);

// Define routes
$routes = array(
    '/get-all' => 'getAll',
    '/get-one' => 'getOne',
    '/create' => 'create',
    '/update' => 'update',
    '/delete' => 'delete'
);

// Get route
$route = $_SERVER['REQUEST_URI'];
$route = explode('/', $route);
array_shift($route); // Remove empty string
array_shift($route); // Remove 'اختبارات.php'
$route = implode('/', $route);

// Check if route exists
if (!isset($routes[$route])) {
    http_response_code(404);
    echo json_encode(array('error' => 'Route not found'));
    exit;
}

// Call route function
$func = $routes[$route];
$func();

// Helper function to get user role
function getUserRole() {
    global $conn;
    $stmt = $conn->prepare('SELECT role FROM users WHERE id = :id');
    $stmt->bindParam(':id', $_SESSION['user_id']);
    $stmt->execute();
    return $stmt->fetchColumn();
}

// Helper function to validate input
function validateInput($input) {
    // Add validation rules here
    return true;
}

// Helper function to sanitize input
function sanitizeInput($input) {
    // Add sanitization rules here
    return $input;
}

// getAll route
function getAll() {
    global $conn;
    $stmt = $conn->prepare('SELECT * FROM اختبارات');
    $stmt->execute();
    $rows = $stmt->fetchAll();
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode($rows);
}

// getOne route
function getOne() {
    global $conn;
    $id = sanitizeInput($input['id']);
    $stmt = $conn->prepare('SELECT * FROM اختبارات WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $row = $stmt->fetch();
    if (!$row) {
        http_response_code(404);
        echo json_encode(array('error' => 'Not found'));
        exit;
    }
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode($row);
}

// create route
function create() {
    global $conn;
    if (!validateInput($input)) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid input'));
        exit;
    }
    $input = sanitizeInput($input);
    $stmt = $conn->prepare('INSERT INTO اختبارات SET :data');
    $data = array();
    foreach ($input as $key => $value) {
        $data[':' . $key] = $value;
    }
    $stmt->execute($data);
    http_response_code(201);
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'Created successfully'));
}

// update route
function update() {
    global $conn;
    if (!validateInput($input)) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid input'));
        exit;
    }
    $input = sanitizeInput($input);
    $id = $input['id'];
    $stmt = $conn->prepare('UPDATE اختبارات SET :data WHERE id = :id');
    $data = array();
    foreach ($input as $key => $value) {
        if ($key != 'id') {
            $data[':' . $key] = $value;
        }
    }
    $data[':id'] = $id;
    $stmt->execute($data);
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'Updated successfully'));
}

// delete route
function delete() {
    global $conn;
    $id = sanitizeInput($input['id']);
    $role = getUserRole();
    if ($role != 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }
    $stmt = $conn->prepare('DELETE FROM اختبارات WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'Deleted successfully'));
}