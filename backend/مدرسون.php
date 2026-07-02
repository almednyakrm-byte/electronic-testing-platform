<?php
require_once 'db.php';

// Get the request method
$method = $_SERVER['REQUEST_METHOD'];

// Check if the user is logged in
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    http_response_code(401);
    echo json_encode(array('error' => 'Unauthorized'));
    exit;
}

// Check if the user is an admin
if ($method === 'PUT' || $method === 'DELETE') {
    if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }
}

// Get the input data
$input = json_decode(file_get_contents('php://input'), true);

// Validate the input data
if ($method === 'POST') {
    if (!isset($input['name']) || !isset($input['email']) || !isset($input['phone'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid request'));
        exit;
    }
}

// Sanitize the input data
$name = filter_var($input['name'], FILTER_SANITIZE_STRING);
$email = filter_var($input['email'], FILTER_SANITIZE_EMAIL);
$phone = filter_var($input['phone'], FILTER_SANITIZE_NUMBER_INT);

// Prepare the SQL query
if ($method === 'GET') {
    $stmt = $pdo->prepare('SELECT * FROM مدرسون');
} elseif ($method === 'POST') {
    $stmt = $pdo->prepare('INSERT INTO مدرسون (name, email, phone) VALUES (:name, :email, :phone)');
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':phone', $phone);
} elseif ($method === 'PUT') {
    $stmt = $pdo->prepare('UPDATE مدرسون SET name = :name, email = :email, phone = :phone WHERE id = :id');
    $stmt->bindParam(':id', $input['id']);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':phone', $phone);
} elseif ($method === 'DELETE') {
    $stmt = $pdo->prepare('DELETE FROM مدرسون WHERE id = :id');
    $stmt->bindParam(':id', $input['id']);
}

// Execute the SQL query
try {
    if ($method === 'GET') {
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode($result);
    } elseif ($method === 'POST') {
        $stmt->execute();
        $id = $pdo->lastInsertId();
        http_response_code(201);
        header('Content-Type: application/json');
        echo json_encode(array('id' => $id));
    } elseif ($method === 'PUT') {
        $stmt->execute();
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode(array('message' => 'Updated successfully'));
    } elseif ($method === 'DELETE') {
        $stmt->execute();
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode(array('message' => 'Deleted successfully'));
    }
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(array('error' => 'Internal Server Error'));
}