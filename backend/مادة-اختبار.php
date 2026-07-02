<?php
require_once 'db.php';

// Get user role and ID from session
$userRole = $_SESSION['userRole'];
$userID = $_SESSION['userID'];

// Get input data
$inputData = json_decode(file_get_contents('php://input'), true);

// Handle GET request
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if ($userRole == 'admin') {
        // Select all records
        $stmt = $pdo->prepare("SELECT * FROM مادة_اختبار");
        $stmt->execute();
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        header('Content-Type: application/json');
        http_response_code(200);
        echo json_encode($data);
    } else {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
    }
} elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Validate input data
    if (!isset($inputData['name']) || !isset($inputData['description'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid request'));
        exit;
    }

    // Sanitize input data
    $name = htmlspecialchars($inputData['name']);
    $description = htmlspecialchars($inputData['description']);

    // Insert new record
    $stmt = $pdo->prepare("INSERT INTO مادة_اختبار (name, description) VALUES (:name, :description)");
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':description', $description);
    $stmt->execute();

    // Return success response
    http_response_code(201);
    echo json_encode(array('message' => 'Record created successfully'));
} elseif ($_SERVER['REQUEST_METHOD'] == 'PUT') {
    // Validate input data
    if (!isset($inputData['id']) || !isset($inputData['name']) || !isset($inputData['description'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid request'));
        exit;
    }

    // Sanitize input data
    $id = intval($inputData['id']);
    $name = htmlspecialchars($inputData['name']);
    $description = htmlspecialchars($inputData['description']);

    // Check if user is admin
    if ($userRole != 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }

    // Update existing record
    $stmt = $pdo->prepare("UPDATE مادة_اختبار SET name = :name, description = :description WHERE id = :id");
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':description', $description);
    $stmt->execute();

    // Return success response
    http_response_code(200);
    echo json_encode(array('message' => 'Record updated successfully'));
} elseif ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
    // Validate input data
    if (!isset($inputData['id'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid request'));
        exit;
    }

    // Sanitize input data
    $id = intval($inputData['id']);

    // Check if user is admin
    if ($userRole != 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }

    // Delete existing record
    $stmt = $pdo->prepare("DELETE FROM مادة_اختبار WHERE id = :id");
    $stmt->bindParam(':id', $id);
    $stmt->execute();

    // Return success response
    http_response_code(200);
    echo json_encode(array('message' => 'Record deleted successfully'));
} else {
    http_response_code(405);
    echo json_encode(array('error' => 'Method not allowed'));
}