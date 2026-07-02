**create_مستخدم.php**

<?php
// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit;
}

// Include database connection
require_once '../backend/config.php';

// Check if form is submitted
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
        // Insert new user
        $sql = "INSERT INTO مستخدم (name, email, password, role) VALUES ('$name', '$email', '$password', '$role')";
        $result = mysqli_query($conn, $sql);

        if ($result) {
            // Redirect back to list page
            header('Location: list_مستخدم.php');
            exit;
        } else {
            $error = 'Error creating user';
        }
    }
}

// Close database connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إضافة مستخدم</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        .emerald-600 { color: #008E77; }
        .teal-500 { color: #0097A7; }
    </style>
</head>
<body class="bg-gray-100">
    <div class="max-w-md mx-auto p-4 bg-white rounded-md shadow-md">
        <h2 class="text-lg font-bold text-emerald-600 mb-4">إضافة مستخدم</h2>
        <form id="create-user-form" method="post">
            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-gray-700">اسم المستخدم:</label>
                <input type="text" id="name" name="name" class="block w-full px-4 py-2 text-gray-700 border border-gray-300 rounded-md focus:ring-teal-500 focus:border-teal-500" required>
            </div>
            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-gray-700">البريد الإلكتروني:</label>
                <input type="email" id="email" name="email" class="block w-full px-4 py-2 text-gray-700 border border-gray-300 rounded-md focus:ring-teal-500 focus:border-teal-500" required>
            </div>
            <div class="mb-4">
                <label for="password" class="block text-sm font-medium text-gray-700">كلمة المرور:</label>
                <input type="password" id="password" name="password" class="block w-full px-4 py-2 text-gray-700 border border-gray-300 rounded-md focus:ring-teal-500 focus:border-teal-500" required>
            </div>
            <div class="mb-4">
                <label for="role" class="block text-sm font-medium text-gray-700">دور المستخدم:</label>
                <select id="role" name="role" class="block w-full px-4 py-2 text-gray-700 border border-gray-300 rounded-md focus:ring-teal-500 focus:border-teal-500">
                    <option value="">اختر دور</option>
                    <option value="admin">مدير</option>
                    <option value="moderator">مدير محتوى</option>
                    <option value="user">مستخدم عادي</option>
                </select>
            </div>
            <button type="submit" id="submit-btn" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2 px-4 rounded-md">إضافة مستخدم</button>
        </form>
        <?php if (isset($error)) : ?>
            <p class="text-red-500 mt-4"><?= $error ?></p>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#create-user-form').submit(function(e) {
                e.preventDefault();
                $.ajax({
                    type: 'POST',
                    url: '../backend/مستخدم.php',
                    data: $(this).serialize(),
                    success: function(response) {
                        if (response === 'success') {
                            window.location.href = 'list_مستخدم.php';
                        } else {
                            alert('Error creating user');
                        }
                    }
                });
            });
        });
    </script>
</body>
</html>

Note: This code assumes that you have a database connection established in `config.php` and that the `مستخدم` table exists in the database. Also, this code uses the `mysqli` extension for database interactions.