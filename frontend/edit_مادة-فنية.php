**edit_مادة-فنية.php**

<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    header('Location: login.php');
    exit;
}

// Get ID from URL
$id = $_GET['id'];

// Fetch existing record details
$existingRecord = json_decode(file_get_contents('../backend/مادة-فنية.php?id=' . $id), true);

?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تعديل مادة فنية</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="max-w-md mx-auto p-4 bg-white rounded-md shadow-md">
        <h1 class="text-lg font-bold text-emerald-600 mb-4">تعديل مادة فنية</h1>
        <form id="edit-form" class="space-y-4">
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700">اسم المادة الفنية</label>
                <input type="text" id="name" name="name" value="<?= $existingRecord['name'] ?>" class="block w-full px-4 py-2 text-gray-700 border border-gray-300 rounded-md focus:ring-teal-500 focus:border-teal-500">
            </div>
            <div>
                <label for="description" class="block text-sm font-medium text-gray-700">وصف المادة الفنية</label>
                <textarea id="description" name="description" class="block w-full px-4 py-2 text-gray-700 border border-gray-300 rounded-md focus:ring-teal-500 focus:border-teal-500"><?= $existingRecord['description'] ?></textarea>
            </div>
            <button type="submit" class="w-full px-4 py-2 text-lg font-bold text-white bg-emerald-600 hover:bg-emerald-700 rounded-md">تعديل</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#edit-form').submit(function(e) {
                e.preventDefault();
                var formData = $(this).serialize();
                $.ajax({
                    type: 'PUT',
                    url: '../backend/مادة-فنية.php',
                    data: formData,
                    success: function(response) {
                        if (response.success) {
                            window.location.href = 'list_<?= $_SESSION['mod_slug'] ?>.php';
                        } else {
                            alert('Error: ' + response.message);
                        }
                    }
                });
            });
        });
    </script>
</body>
</html>


**مادة-فنية.php (backend)**

<?php
// Check if ID is set
if (!isset($_GET['id'])) {
    die('Error: ID is required');
}

// Get ID
$id = $_GET['id'];

// Fetch existing record details
$existingRecord = array(
    'name' => 'مادة فنية',
    'description' => 'وصف المادة الفنية',
    // Add more fields as needed
);

// Return JSON response
echo json_encode($existingRecord);


Note: You'll need to replace `list_<?= $_SESSION['mod_slug'] ?>.php` with the actual URL of the list page. Also, make sure to update the `مادة-فنية.php` backend file to return the existing record details in JSON format.