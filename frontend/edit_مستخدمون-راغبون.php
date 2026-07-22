**edit_مستخدمون-راغبون.php**

<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

// Get id from URL
$id = $_GET['id'];

// Check if id is set
if (!$id) {
    header('Location: list_مستخدمون-راغبون.php');
    exit;
}

// Fetch existing record details via GET
$existingRecord = json_decode(file_get_contents('../backend/مستخدمون-راغبون.php?id=' . $id), true);

// Check if record exists
if (!$existingRecord) {
    header('Location: list_مستخدمون-راغبون.php');
    exit;
}

?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تعديل مستخدم</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="max-w-md mx-auto p-4 bg-white rounded-md shadow-md">
        <h2 class="text-lg font-bold text-emerald-600 mb-4">تعديل مستخدم</h2>
        <form id="edit-user-form">
            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-gray-700">اسم المستخدم</label>
                <input type="text" id="name" name="name" class="block w-full p-2 pl-10 text-sm text-gray-700 bg-gray-50 rounded-lg border border-gray-300 focus:ring-emerald-600 focus:border-emerald-600" value="<?= $existingRecord['name'] ?>">
            </div>
            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-gray-700">البريد الإلكتروني</label>
                <input type="email" id="email" name="email" class="block w-full p-2 pl-10 text-sm text-gray-700 bg-gray-50 rounded-lg border border-gray-300 focus:ring-emerald-600 focus:border-emerald-600" value="<?= $existingRecord['email'] ?>">
            </div>
            <div class="mb-4">
                <label for="phone" class="block text-sm font-medium text-gray-700">رقم الهاتف</label>
                <input type="tel" id="phone" name="phone" class="block w-full p-2 pl-10 text-sm text-gray-700 bg-gray-50 rounded-lg border border-gray-300 focus:ring-emerald-600 focus:border-emerald-600" value="<?= $existingRecord['phone'] ?>">
            </div>
            <button type="submit" class="bg-teal-500 hover:bg-teal-700 text-white font-bold py-2 px-4 rounded">تعديل</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#edit-user-form').submit(function(e) {
                e.preventDefault();
                $.ajax({
                    type: 'PUT',
                    url: '../backend/مستخدمون-راغبون.php',
                    data: $(this).serialize(),
                    success: function(data) {
                        if (data === 'success') {
                            window.location.href = 'list_مستخدمون-راغبون.php';
                        } else {
                            alert('Error: ' + data);
                        }
                    }
                });
            });
        });
    </script>
</body>
</html>


**../backend/مستخدمون-راغبون.php**

<?php
// Check if id is set
if (isset($_GET['id'])) {
    // Fetch existing record details from database
    $id = $_GET['id'];
    $existingRecord = // fetch record from database using $id
    echo json_encode($existingRecord);
} else {
    echo 'Error: ID not set';
}
?>