<?php

// Import database connection settings
require_once 'db.php';

// Check if user is logged in
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    http_response_code(401);
    echo json_encode(array('error' => 'Unauthorized'));
    exit;
}

// Get user role
$userRole = $_SESSION['role'];

// Check if user is admin
$isAdmin = ($userRole == 'admin');

// Get input data
$inputData = json_decode(file_get_contents('php://input'), true);

// Validate input data
if (!$inputData) {
    http_response_code(400);
    echo json_encode(array('error' => 'Invalid request data'));
    exit;
}

// Define database table name
$tableName = 'مؤسسات';

// Define CRUD operations
$operations = array(
    'GET' => function() use ($tableName, $isAdmin) {
        // Select all records
        $query = "SELECT * FROM $tableName";
        if (!$isAdmin) {
            $query .= " WHERE user_id = :user_id";
        }
        $stmt = $pdo->prepare($query);
        if (!$isAdmin) {
            $stmt->bindParam(':user_id', $_SESSION['id']);
        }
        $stmt->execute();
        $records = $stmt->fetchAll(PDO::FETCH_ASSOC);
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode($records);
    },
    'POST' => function() use ($tableName, $pdo, $inputData) {
        // Insert new record
        $query = "INSERT INTO $tableName (name, address, phone) VALUES (:name, :address, :phone)";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':name', $inputData['name']);
        $stmt->bindParam(':address', $inputData['address']);
        $stmt->bindParam(':phone', $inputData['phone']);
        $stmt->execute();
        http_response_code(201);
        header('Content-Type: application/json');
        echo json_encode(array('message' => 'Record created successfully'));
    },
    'PUT' => function() use ($tableName, $pdo, $inputData, $isAdmin) {
        // Update existing record
        $query = "UPDATE $tableName SET name = :name, address = :address, phone = :phone WHERE id = :id";
        if (!$isAdmin) {
            $query .= " AND user_id = :user_id";
        }
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':id', $inputData['id']);
        $stmt->bindParam(':name', $inputData['name']);
        $stmt->bindParam(':address', $inputData['address']);
        $stmt->bindParam(':phone', $inputData['phone']);
        if (!$isAdmin) {
            $stmt->bindParam(':user_id', $_SESSION['id']);
        }
        $stmt->execute();
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode(array('message' => 'Record updated successfully'));
    },
    'DELETE' => function() use ($tableName, $pdo, $inputData, $isAdmin) {
        // Delete existing record
        $query = "DELETE FROM $tableName WHERE id = :id";
        if (!$isAdmin) {
            $query .= " AND user_id = :user_id";
        }
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':id', $inputData['id']);
        if (!$isAdmin) {
            $stmt->bindParam(':user_id', $_SESSION['id']);
        }
        $stmt->execute();
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode(array('message' => 'Record deleted successfully'));
    }
);

// Determine operation based on request method
$method = $_SERVER['REQUEST_METHOD'];
if (!isset($operations[$method])) {
    http_response_code(405);
    echo json_encode(array('error' => 'Method not allowed'));
    exit;
}

// Execute operation
$operations[$method]();

?>