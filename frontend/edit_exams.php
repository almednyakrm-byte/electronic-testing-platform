**edit_exams.php**

<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Get exam ID from URL
$id = $_GET['id'];

// Fetch existing record details via GET
$exam = json_decode(file_get_contents('../backend/exams.php?id=' . $id), true);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Exam</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="max-w-md mx-auto p-4 bg-white rounded-lg shadow-md">
        <h2 class="text-lg font-bold text-emerald-600 mb-4">Edit Exam</h2>
        <form id="exam-form">
            <div class="mb-4">
                <label for="title" class="block text-sm font-medium text-gray-700">Title</label>
                <input type="text" id="title" name="title" class="block w-full px-4 py-2 text-gray-700 border border-gray-300 rounded-lg focus:ring-emerald-600 focus:border-emerald-600" value="<?= $exam['title'] ?>">
            </div>
            <div class="mb-4">
                <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                <textarea id="description" name="description" class="block w-full px-4 py-2 text-gray-700 border border-gray-300 rounded-lg focus:ring-emerald-600 focus:border-emerald-600"><?= $exam['description'] ?></textarea>
            </div>
            <div class="mb-4">
                <label for="date" class="block text-sm font-medium text-gray-700">Date</label>
                <input type="date" id="date" name="date" class="block w-full px-4 py-2 text-gray-700 border border-gray-300 rounded-lg focus:ring-emerald-600 focus:border-emerald-600" value="<?= $exam['date'] ?>">
            </div>
            <button type="submit" class="bg-teal-500 hover:bg-teal-700 text-white font-bold py-2 px-4 rounded-lg">Update Exam</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#exam-form').submit(function(e) {
                e.preventDefault();
                var formData = $(this).serialize();
                $.ajax({
                    type: 'PUT',
                    url: '../backend/exams.php',
                    data: formData,
                    success: function(response) {
                        if (response.success) {
                            window.location.href = 'list_mod_slug.php';
                        } else {
                            alert('Error updating exam');
                        }
                    }
                });
            });
        });
    </script>
</body>
</html>


**Note:** Replace `list_mod_slug.php` with the actual URL of the page you want to redirect to after updating the exam. Also, make sure to update the `exams.php` file in the `backend` directory to handle the PUT request and update the exam record accordingly.