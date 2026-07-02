**create_مادة-فنية.php**

<?php
// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit;
}

// Include database connection
require_once '../backend/db.php';

// Check if form has been submitted
if (isset($_POST['submit'])) {
    // Validate form data
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $category = trim($_POST['category']);
    $status = trim($_POST['status']);

    // Check if all fields are filled
    if (!empty($name) && !empty($description) && !empty($category) && !empty($status)) {
        // Insert data into database
        $query = "INSERT INTO مادة_فنية (name, description, category, status) VALUES ('$name', '$description', '$category', '$status')";
        $result = mysqli_query($conn, $query);

        if ($result) {
            // Redirect back to list page
            header('Location: list_مادة-فنية.php');
            exit;
        } else {
            echo 'Error inserting data';
        }
    } else {
        echo 'Please fill all fields';
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
    <title>إضافة مادة فنية جديدة</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        .emerald-600 {
            color: #008E77;
        }
        .teal-500 {
            color: #0097A7;
        }
    </style>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto p-4">
        <h1 class="text-3xl font-bold text-emerald-600 mb-4">إضافة مادة فنية جديدة</h1>
        <form id="create-form" class="bg-white p-4 rounded shadow-md">
            <div class="mb-4">
                <label for="name" class="block text-gray-700 text-sm font-bold mb-2">اسم المادة الفنية:</label>
                <input type="text" id="name" name="name" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="اسم المادة الفنية">
            </div>
            <div class="mb-4">
                <label for="description" class="block text-gray-700 text-sm font-bold mb-2">وصف المادة الفنية:</label>
                <textarea id="description" name="description" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="وصف المادة الفنية"></textarea>
            </div>
            <div class="mb-4">
                <label for="category" class="block text-gray-700 text-sm font-bold mb-2">فئة المادة الفنية:</label>
                <select id="category" name="category" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    <option value="">اختر فئة</option>
                    <option value="فئة 1">فئة 1</option>
                    <option value="فئة 2">فئة 2</option>
                    <option value="فئة 3">فئة 3</option>
                </select>
            </div>
            <div class="mb-4">
                <label for="status" class="block text-gray-700 text-sm font-bold mb-2">حالة المادة الفنية:</label>
                <select id="status" name="status" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    <option value="">اختر حالة</option>
                    <option value="نشط">نشط</option>
                    <option value="مغلق">مغلق</option>
                </select>
            </div>
            <button type="submit" id="submit-btn" class="bg-teal-500 hover:bg-teal-700 text-white font-bold py-2 px-4 rounded">حفظ</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#create-form').submit(function(e) {
                e.preventDefault();
                var formData = $(this).serialize();
                $.ajax({
                    type: 'POST',
                    url: '../backend/مادة-فنية.php',
                    data: formData,
                    success: function(response) {
                        if (response === 'true') {
                            window.location.href = 'list_مادة-فنية.php';
                        } else {
                            alert('Error creating record');
                        }
                    }
                });
            });
        });
    </script>
</body>
</html>

**مادة-فنية.php (backend file)**

<?php
// Include database connection
require_once '../backend/db.php';

// Check if form data has been submitted
if (isset($_POST['submit'])) {
    // Validate form data
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $category = trim($_POST['category']);
    $status = trim($_POST['status']);

    // Insert data into database
    $query = "INSERT INTO مادة_فنية (name, description, category, status) VALUES ('$name', '$description', '$category', '$status')";
    $result = mysqli_query($conn, $query);

    if ($result) {
        echo 'true';
    } else {
        echo 'false';
    }
}

// Close database connection
mysqli_close($conn);
?>