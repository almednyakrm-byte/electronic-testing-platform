**edit_مدرسون.php**

<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

// Get id from URL
$id = $_GET['id'];

// Fetch existing record details via GET
$url = '../backend/مدرسون.php?id=' . $id;
$response = file_get_contents($url);
$data = json_decode($response, true);

// Check if data exists
if ($data) {
    $name = $data['name'];
    $email = $data['email'];
    $phone = $data['phone'];
} else {
    echo 'Error fetching data';
    exit;
}

?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تعديل مدرس</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="max-w-md mx-auto p-4 bg-white rounded-md shadow-md">
        <h2 class="text-lg font-bold text-emerald-600 mb-4">تعديل مدرس</h2>
        <form id="edit-form">
            <div class="mb-4">
                <label for="name" class="block text-sm font-bold text-gray-700">اسم المدرس</label>
                <input type="text" id="name" name="name" class="w-full p-2 text-sm text-gray-700 border border-gray-300 rounded-md" value="<?= $name ?>">
            </div>
            <div class="mb-4">
                <label for="email" class="block text-sm font-bold text-gray-700">البريد الإلكتروني</label>
                <input type="email" id="email" name="email" class="w-full p-2 text-sm text-gray-700 border border-gray-300 rounded-md" value="<?= $email ?>">
            </div>
            <div class="mb-4">
                <label for="phone" class="block text-sm font-bold text-gray-700">رقم الهاتف</label>
                <input type="tel" id="phone" name="phone" class="w-full p-2 text-sm text-gray-700 border border-gray-300 rounded-md" value="<?= $phone ?>">
            </div>
            <button type="submit" class="bg-teal-500 hover:bg-teal-700 text-white font-bold py-2 px-4 rounded">تعديل</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#edit-form').submit(function(e) {
                e.preventDefault();
                var formData = $(this).serialize();
                $.ajax({
                    type: 'PUT',
                    url: '../backend/مدرسون.php',
                    data: formData,
                    success: function(response) {
                        if (response === 'success') {
                            window.location.href = 'list_<?= $mod_slug ?>.php';
                        } else {
                            alert('Error updating record');
                        }
                    }
                });
            });
        });
    </script>
</body>
</html>


**Note:** Replace `<?= $mod_slug ?>` with the actual value of the `mod_slug` variable. Also, make sure to update the `../backend/مدرسون.php` file to handle the PUT request and update the record accordingly.