**create_مستخدمون.php**

<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit;
}

// Include database connection
require_once '../config/database.php';

// Check if form has been submitted
if (isset($_POST['submit'])) {
    // Validate form data
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $role = trim($_POST['role']);

    // Check for empty fields
    if (empty($name) || empty($email) || empty($password) || empty($role)) {
        $error = 'Please fill in all fields';
    } else {
        // Insert new record into database
        $query = "INSERT INTO مستخدمون (name, email, password, role) VALUES ('$name', '$email', '$password', '$role')";
        $result = mysqli_query($conn, $query);

        if ($result) {
            // Redirect back to list page
            header('Location: list_مستخدمون.php');
            exit;
        } else {
            $error = 'Error creating new record';
        }
    }
}

// Include header and navigation
require_once '../includes/header.php';

?>

<!-- Create new user form -->
<div class="max-w-md mx-auto p-8 bg-white rounded-lg shadow-md">
    <h2 class="text-lg font-bold text-emerald-600 mb-4">Create New User</h2>
    <form id="create-user-form" method="post">
        <div class="mb-4">
            <label for="name" class="block text-sm font-bold text-gray-700">Name:</label>
            <input type="text" id="name" name="name" class="w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-teal-500 focus:border-teal-500" required>
        </div>
        <div class="mb-4">
            <label for="email" class="block text-sm font-bold text-gray-700">Email:</label>
            <input type="email" id="email" name="email" class="w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-teal-500 focus:border-teal-500" required>
        </div>
        <div class="mb-4">
            <label for="password" class="block text-sm font-bold text-gray-700">Password:</label>
            <input type="password" id="password" name="password" class="w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-teal-500 focus:border-teal-500" required>
        </div>
        <div class="mb-4">
            <label for="role" class="block text-sm font-bold text-gray-700">Role:</label>
            <select id="role" name="role" class="w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-teal-500 focus:border-teal-500" required>
                <option value="">Select Role</option>
                <option value="admin">Admin</option>
                <option value="moderator">Moderator</option>
                <option value="user">User</option>
            </select>
        </div>
        <button type="submit" name="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2 px-4 rounded-md">Create User</button>
    </form>
    <?php if (isset($error)) : ?>
        <p class="text-red-500 mt-2"><?= $error ?></p>
    <?php endif; ?>
</div>

<!-- Include footer -->
<?php require_once '../includes/footer.php'; ?>

<script>
    $(document).ready(function() {
        $('#create-user-form').submit(function(e) {
            e.preventDefault();
            $.ajax({
                type: 'POST',
                url: '../backend/مستخدمون.php',
                data: $(this).serialize(),
                success: function(response) {
                    if (response === 'success') {
                        window.location.href = 'list_مستخدمون.php';
                    } else {
                        alert('Error creating new record');
                    }
                }
            });
        });
    });
</script>

This code creates a premium Tailwind UI form with all necessary fields for the 'مستخدمون' module. It includes session validation and uses AJAX to POST the form data to the backend script. On success, it redirects back to the list page.