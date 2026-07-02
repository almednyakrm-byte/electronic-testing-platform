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

// Handle GET request
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Validate and sanitize input
    $limit = filter_var($_GET['limit'], FILTER_VALIDATE_INT);
    $offset = filter_var($_GET['offset'], FILTER_VALIDATE_INT);

    // Prepare SQL query
    $sql = "SELECT * FROM students";
    if ($limit && $offset) {
        $sql .= " LIMIT :limit OFFSET :offset";
    }

    // Execute query
    $stmt = $pdo->prepare($sql);
    if ($limit && $offset) {
        $stmt->bindParam(':limit', $limit);
        $stmt->bindParam(':offset', $offset);
    }
    $stmt->execute();

    // Fetch and return data
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode($data);
}

// Handle POST request
elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Read input data
    $input_data = json_decode(file_get_contents('php://input'), true);

    // Validate and sanitize input
    $name = filter_var($input_data['name'], FILTER_SANITIZE_STRING);
    $email = filter_var($input_data['email'], FILTER_SANITIZE_EMAIL);
    $phone = filter_var($input_data['phone'], FILTER_SANITIZE_NUMBER_INT);

    // Check if user is admin
    if ($user_role !== 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }

    // Prepare SQL query
    $sql = "INSERT INTO students (name, email, phone) VALUES (:name, :email, :phone)";

    // Execute query
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':phone', $phone);
    $stmt->execute();

    // Return success response
    http_response_code(201);
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'Student created successfully'));
}

// Handle PUT request
elseif ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    // Read input data
    $input_data = json_decode(file_get_contents('php://input'), true);

    // Validate and sanitize input
    $id = filter_var($input_data['id'], FILTER_VALIDATE_INT);
    $name = filter_var($input_data['name'], FILTER_SANITIZE_STRING);
    $email = filter_var($input_data['email'], FILTER_SANITIZE_EMAIL);
    $phone = filter_var($input_data['phone'], FILTER_SANITIZE_NUMBER_INT);

    // Check if user is admin
    if ($user_role !== 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }

    // Prepare SQL query
    $sql = "UPDATE students SET name = :name, email = :email, phone = :phone WHERE id = :id";

    // Execute query
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':phone', $phone);
    $stmt->execute();

    // Return success response
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'Student updated successfully'));
}

// Handle DELETE request
elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    // Read input data
    $input_data = json_decode(file_get_contents('php://input'), true);

    // Validate and sanitize input
    $id = filter_var($input_data['id'], FILTER_VALIDATE_INT);

    // Check if user is admin
    if ($user_role !== 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }

    // Prepare SQL query
    $sql = "DELETE FROM students WHERE id = :id";

    // Execute query
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id);
    $stmt->execute();

    // Return success response
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'Student deleted successfully'));
}

// Return error response for invalid request method
else {
    http_response_code(405);
    echo json_encode(array('error' => 'Method Not Allowed'));
}