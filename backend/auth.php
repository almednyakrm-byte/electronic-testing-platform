<?php
// Start the session to handle user authentication
session_start();

// Import the database connection script
require_once 'db.php';

// Check if the user is already logged in
if (isset($_SESSION['user_id'])) {
    // If the user is logged in, return a JSON response indicating their status
    echo json_encode(array('status' => 'logged_in', 'user_id' => $_SESSION['user_id']));
    exit;
}

// Check if the user is attempting to register or login
if (isset($_POST['action'])) {
    // Check if the action is to register a new user
    if ($_POST['action'] == 'register') {
        // Check if all required fields are present
        if (isset($_POST['username']) && isset($_POST['email']) && isset($_POST['password'])) {
            // Sanitize and validate user input
            $username = filter_var($_POST['username'], FILTER_SANITIZE_STRING);
            $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
            $password = filter_var($_POST['password'], FILTER_SANITIZE_STRING);

            // Check if the username and email are unique
            $query = $db->prepare("SELECT * FROM users WHERE username = :username OR email = :email");
            $query->bindParam(':username', $username);
            $query->bindParam(':email', $email);
            $query->execute();
            if ($query->rowCount() > 0) {
                // If the username or email is already taken, return an error message
                echo json_encode(array('status' => 'error', 'message' => 'Username or email already taken'));
                exit;
            }

            // Hash the user's password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Insert the new user into the database
            $query = $db->prepare("INSERT INTO users (username, email, password) VALUES (:username, :email, :password)");
            $query->bindParam(':username', $username);
            $query->bindParam(':email', $email);
            $query->bindParam(':password', $hashed_password);
            $query->execute();

            // Return a JSON response indicating that the user has been registered
            echo json_encode(array('status' => 'success', 'message' => 'User registered successfully'));
            exit;
        } else {
            // If any required fields are missing, return an error message
            echo json_encode(array('status' => 'error', 'message' => 'Missing required fields'));
            exit;
        }
    }

    // Check if the action is to login an existing user
    elseif ($_POST['action'] == 'login') {
        // Check if all required fields are present
        if (isset($_POST['username']) && isset($_POST['password'])) {
            // Sanitize and validate user input
            $username = filter_var($_POST['username'], FILTER_SANITIZE_STRING);
            $password = filter_var($_POST['password'], FILTER_SANITIZE_STRING);

            // Check if the username exists in the database
            $query = $db->prepare("SELECT * FROM users WHERE username = :username");
            $query->bindParam(':username', $username);
            $query->execute();
            $user = $query->fetch();

            // If the username does not exist, return an error message
            if (!$user) {
                echo json_encode(array('status' => 'error', 'message' => 'Invalid username or password'));
                exit;
            }

            // Verify the user's password
            if (password_verify($password, $user['password'])) {
                // If the password is correct, log the user in and return a JSON response
                $_SESSION['user_id'] = $user['id'];
                echo json_encode(array('status' => 'success', 'message' => 'User logged in successfully'));
                exit;
            } else {
                // If the password is incorrect, return an error message
                echo json_encode(array('status' => 'error', 'message' => 'Invalid username or password'));
                exit;
            }
        } else {
            // If any required fields are missing, return an error message
            echo json_encode(array('status' => 'error', 'message' => 'Missing required fields'));
            exit;
        }
    }
}

// Check if the user is attempting to logout
if (isset($_GET['logout'])) {
    // Destroy the user's session and return a JSON response
    session_destroy();
    echo json_encode(array('status' => 'success', 'message' => 'User logged out successfully'));
    exit;
}


This script handles user registration, login, logout, and session status checks. It uses prepared statements to prevent SQL injection and password hashing to store passwords securely. It also checks for missing required fields and returns error messages accordingly.