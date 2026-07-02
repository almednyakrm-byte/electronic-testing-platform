<?php
// Start the session to handle user authentication
session_start();

// Include the database connection file
require_once 'db.php';

// Check if the user is already logged in
if (isset($_SESSION['user_id'])) {
    // If the user is logged in, return a JSON response with their details
    $user_id = $_SESSION['user_id'];
    $query = "SELECT * FROM users WHERE id = ?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    echo json_encode(array('status' => 'logged_in', 'user' => $user));
    exit;
}

// Handle the login request
if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['action'] == 'login') {
    // Check if the username and password are provided
    if (!isset($_POST['username']) || !isset($_POST['password'])) {
        echo json_encode(array('status' => 'error', 'message' => 'Username and password are required'));
        exit;
    }

    // Sanitize the input fields
    $username = filter_var($_POST['username'], FILTER_SANITIZE_STRING);
    $password = filter_var($_POST['password'], FILTER_SANITIZE_STRING);

    // Prepare the SQL query to check the username and password
    $query = "SELECT * FROM users WHERE username = ?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    // Check if the user exists and the password is correct
    if ($user && password_verify($password, $user['password'])) {
        // If the user exists and the password is correct, log them in
        $_SESSION['user_id'] = $user['id'];
        echo json_encode(array('status' => 'logged_in'));
    } else {
        // If the user does not exist or the password is incorrect, return an error message
        echo json_encode(array('status' => 'error', 'message' => 'Invalid username or password'));
    }
    exit;
}

// Handle the registration request
if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['action'] == 'register') {
    // Check if the username, email, and password are provided
    if (!isset($_POST['username']) || !isset($_POST['email']) || !isset($_POST['password'])) {
        echo json_encode(array('status' => 'error', 'message' => 'Username, email, and password are required'));
        exit;
    }

    // Sanitize the input fields
    $username = filter_var($_POST['username'], FILTER_SANITIZE_STRING);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $password = filter_var($_POST['password'], FILTER_SANITIZE_STRING);

    // Check if the username and email are unique
    $query = "SELECT * FROM users WHERE username = ? OR email = ?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("ss", $username, $email);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        echo json_encode(array('status' => 'error', 'message' => 'Username or email already exists'));
        exit;
    }

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Prepare the SQL query to insert the new user
    $query = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("sss", $username, $email, $hashed_password);
    $stmt->execute();

    // Return a JSON response with the user's details
    $user_id = $mysqli->insert_id;
    $query = "SELECT * FROM users WHERE id = ?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    echo json_encode(array('status' => 'registered', 'user' => $user));
    exit;
}

// Handle the logout request
if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['action'] == 'logout') {
    // Destroy the session to log the user out
    session_destroy();
    echo json_encode(array('status' => 'logged_out'));
    exit;
}

// If no action is provided, return a JSON response with the current session status
echo json_encode(array('status' => 'logged_out'));
exit;