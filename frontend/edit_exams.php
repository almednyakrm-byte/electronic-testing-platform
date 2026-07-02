**edit_exams.php**

<?php
session_start();

// Validate session
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Get exam ID from URL
$id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);

// Fetch existing record details via GET
$exam = json_decode(file_get_contents('../backend/exams.php?id=' . $id), true);

// Check if exam exists
if (empty($exam)) {
    echo 'Exam not found';
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Exam</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
    <div class="max-w-md mx-auto p-4 bg-white rounded-lg shadow-md">
        <h2 class="text-lg font-bold text-emerald-600 mb-4">Edit Exam</h2>
        <form id="exam-form">
            <div class="mb-4">
                <label for="title" class="block text-sm font-medium text-gray-700">Title</label>
                <input type="text" id="title" name="title" class="block w-full p-2 mt-1 text-sm text-gray-700 border-gray-300 rounded-lg focus:ring-teal-500 focus:border-teal-500" value="<?= $exam['title'] ?>">
            </div>
            <div class="mb-4">
                <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                <textarea id="description" name="description" class="block w-full p-2 mt-1 text-sm text-gray-700 border-gray-300 rounded-lg focus:ring-teal-500 focus:border-teal-500"><?= $exam['description'] ?></textarea>
            </div>
            <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2 px-4 rounded-lg">Update Exam</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
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
                        window.location.href = 'list_mod_slug.php';
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr, status, error);
                    }
                });
            });
        });
    </script>
</body>
</html>

**Note:** Replace `list_mod_slug.php` with the actual URL of the page you want to redirect to after updating the exam. Also, make sure to update the `../backend/exams.php` URL to match your backend API endpoint.