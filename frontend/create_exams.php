**create_exams.php**

<?php
// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

// Include header
include_once 'header.php';

// Include Tailwind CSS
?>
<link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">

<?php
// Include navigation
include_once 'navigation.php';
?>

<div class="container mx-auto p-4 mt-12">
    <h1 class="text-3xl font-bold text-emerald-600">Create New Exam</h1>
    <form id="exam-form" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="exam_name">Exam Name</label>
            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="exam_name" type="text" placeholder="Exam Name">
        </div>
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="exam_description">Exam Description</label>
            <textarea class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="exam_description" placeholder="Exam Description"></textarea>
        </div>
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="exam_date">Exam Date</label>
            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="exam_date" type="date">
        </div>
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="exam_time">Exam Time</label>
            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="exam_time" type="time">
        </div>
        <button class="bg-teal-500 hover:bg-teal-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="button">Create Exam</button>
    </form>
</div>

<?php
// Include footer
include_once 'footer.php';
?>

<script>
    $(document).ready(function() {
        $('#exam-form').submit(function(e) {
            e.preventDefault();
            var formData = $(this).serialize();
            $.ajax({
                type: 'POST',
                url: '../backend/exams.php',
                data: formData,
                success: function(response) {
                    if (response === 'success') {
                        window.location.href = 'list_exams.php';
                    } else {
                        alert('Error creating exam');
                    }
                }
            });
        });
    });
</script>


**header.php**

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exam Management System</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>


**navigation.php**

<nav class="bg-teal-500 text-white p-4">
    <ul class="flex justify-between">
        <li><a href="list_exams.php" class="text-white hover:text-teal-500">Exams</a></li>
        <li><a href="create_exams.php" class="text-white hover:text-teal-500">Create Exam</a></li>
        <li><a href="logout.php" class="text-white hover:text-teal-500">Logout</a></li>
    </ul>
</nav>


**footer.php**

</body>
</html>


**styles.css**
css
body {
    font-family: Arial, sans-serif;
    background-color: #f9f9f9;
}

.container {
    max-width: 800px;
    margin: 40px auto;
    padding: 20px;
    background-color: #fff;
    border: 1px solid #ddd;
    border-radius: 10px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

h1 {
    text-align: center;
}

form {
    margin-top: 20px;
}

label {
    display: block;
    margin-bottom: 10px;
}

input, textarea {
    width: 100%;
    padding: 10px;
    margin-bottom: 20px;
    border: 1px solid #ccc;
    border-radius: 5px;
}

button {
    width: 100%;
    padding: 10px;
    background-color: #4CAF50;
    color: #fff;
    border: none;
    border-radius: 5px;
    cursor: pointer;
}

button:hover {
    background-color: #3e8e41;
}

.error {
    color: #f00;
    font-size: 12px;
    margin-bottom: 10px;
}


Note: This code assumes that you have a `backend/exams.php` file that handles the form submission and creates a new exam record in the database. The `list_exams.php` file is also assumed to be present and displays a list of all exams. The `logout.php` file is also assumed to be present and logs out the user.