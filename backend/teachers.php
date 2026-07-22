<?php

// Import database connection settings
require_once 'db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role'])) {
    http_response_code(401);
    echo json_encode(array('error' => 'Unauthorized'));
    exit;
}

// Get input data from JSON or POST request
$input = json_decode(file_get_contents('php://input'), true) ?: $_POST;

// Define validation rules
$validationRules = array(
    'name' => 'required',
    'email' => 'required|email',
    'phone' => 'required|numeric',
    'subject' => 'required'
);

// Validate input data
foreach ($validationRules as $field => $rules) {
    $input[$field] = trim($input[$field]);
    if (empty($input[$field])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Validation failed'));
        exit;
    }
}

// Connect to database
$db = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASSWORD);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Handle GET request
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Check if user is admin
    if ($_SESSION['role'] !== 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }

    // Get all teachers
    $stmt = $db->prepare('SELECT * FROM teachers');
    $stmt->execute();
    $teachers = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($teachers);
    exit;
}

// Handle POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if user is admin
    if ($_SESSION['role'] !== 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }

    // Insert new teacher
    $stmt = $db->prepare('INSERT INTO teachers (name, email, phone, subject) VALUES (:name, :email, :phone, :subject)');
    $stmt->bindParam(':name', $input['name']);
    $stmt->bindParam(':email', $input['email']);
    $stmt->bindParam(':phone', $input['phone']);
    $stmt->bindParam(':subject', $input['subject']);
    $stmt->execute();
    http_response_code(201);
    echo json_encode(array('message' => 'Teacher created successfully'));
    exit;
}

// Handle PUT request
if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    // Check if user is admin
    if ($_SESSION['role'] !== 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }

    // Update existing teacher
    $teacherId = $_GET['id'];
    $stmt = $db->prepare('UPDATE teachers SET name = :name, email = :email, phone = :phone, subject = :subject WHERE id = :id');
    $stmt->bindParam(':id', $teacherId);
    $stmt->bindParam(':name', $input['name']);
    $stmt->bindParam(':email', $input['email']);
    $stmt->bindParam(':phone', $input['phone']);
    $stmt->bindParam(':subject', $input['subject']);
    $stmt->execute();
    http_response_code(200);
    echo json_encode(array('message' => 'Teacher updated successfully'));
    exit;
}

// Handle DELETE request
if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    // Check if user is admin
    if ($_SESSION['role'] !== 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }

    // Delete teacher
    $teacherId = $_GET['id'];
    $stmt = $db->prepare('DELETE FROM teachers WHERE id = :id');
    $stmt->bindParam(':id', $teacherId);
    $stmt->execute();
    http_response_code(200);
    echo json_encode(array('message' => 'Teacher deleted successfully'));
    exit;
}

// Return 405 Method Not Allowed for unsupported methods
http_response_code(405);
echo json_encode(array('error' => 'Method Not Allowed'));
exit;

?>