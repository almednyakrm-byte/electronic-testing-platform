<?php

require_once 'db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

// Get input data
$input = json_decode(file_get_contents('php://input'), true);

// Define database table name
$tableName = 'مقررات';

// Define columns
$columns = ['id', 'name', 'description'];

// Define validation rules
$validationRules = [
    'name' => 'required',
    'description' => 'required'
];

// Validate input data
foreach ($validationRules as $column => $rule) {
    if (!isset($input[$column]) || !$input[$column]) {
        http_response_code(400);
        echo json_encode(['error' => "Validation failed for $column"]);
        exit;
    }
}

// Sanitize input data
$input = array_map('trim', $input);

// Connect to database
$db = new PDO('sqlite:' . DATABASE);

// Handle GET request
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Check if user is admin
    if ($_SESSION['user_role'] !== 'admin') {
        http_response_code(403);
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }

    // Get all records
    $stmt = $db->prepare("SELECT * FROM $tableName");
    $stmt->execute();
    $records = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Return records
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode($records);
    exit;
}

// Handle POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Insert record
    $stmt = $db->prepare("INSERT INTO $tableName (name, description) VALUES (:name, :description)");
    $stmt->bindParam(':name', $input['name']);
    $stmt->bindParam(':description', $input['description']);
    $stmt->execute();

    // Return inserted record
    http_response_code(201);
    header('Content-Type: application/json');
    echo json_encode(['id' => $db->lastInsertId(), 'name' => $input['name'], 'description' => $input['description']]);
    exit;
}

// Handle PUT request
if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    // Check if user is admin
    if ($_SESSION['user_role'] !== 'admin') {
        http_response_code(403);
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }

    // Get record ID
    $recordId = (int) $_GET['id'];

    // Update record
    $stmt = $db->prepare("UPDATE $tableName SET name = :name, description = :description WHERE id = :id");
    $stmt->bindParam(':id', $recordId);
    $stmt->bindParam(':name', $input['name']);
    $stmt->bindParam(':description', $input['description']);
    $stmt->execute();

    // Return updated record
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(['id' => $recordId, 'name' => $input['name'], 'description' => $input['description']]);
    exit;
}

// Handle DELETE request
if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    // Check if user is admin
    if ($_SESSION['user_role'] !== 'admin') {
        http_response_code(403);
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }

    // Get record ID
    $recordId = (int) $_GET['id'];

    // Delete record
    $stmt = $db->prepare("DELETE FROM $tableName WHERE id = :id");
    $stmt->bindParam(':id', $recordId);
    $stmt->execute();

    // Return deleted record
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(['id' => $recordId]);
    exit;
}

// Return error response
http_response_code(405);
echo json_encode(['error' => 'Method not allowed']);
exit;

?>