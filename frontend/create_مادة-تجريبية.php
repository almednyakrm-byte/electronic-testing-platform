<?php
// create_مادة-تجريبية.php
session_start();
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit;
}

include_once '../config.php';

$mod_slug = 'مادة-تجريبية';
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إضافة مادة تجريبية</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
    <div class="max-w-md mx-auto p-4 mt-10 bg-white rounded-lg shadow-md">
        <h2 class="text-2xl font-bold text-emerald-600 mb-4">إضافة مادة تجريبية</h2>
        <form id="create-form">
            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-gray-700">اسم المادة</label>
                <input type="text" id="name" name="name" class="block w-full p-2 mt-1 text-sm text-gray-700 border border-gray-300 rounded-md focus:outline-none focus:ring-emerald-600 focus:border-emerald-600">
            </div>
            <div class="mb-4">
                <label for="description" class="block text-sm font-medium text-gray-700">وصف المادة</label>
                <textarea id="description" name="description" class="block w-full p-2 mt-1 text-sm text-gray-700 border border-gray-300 rounded-md focus:outline-none focus:ring-emerald-600 focus:border-emerald-600"></textarea>
            </div>
            <div class="mb-4">
                <label for="type" class="block text-sm font-medium text-gray-700">نوع المادة</label>
                <select id="type" name="type" class="block w-full p-2 mt-1 text-sm text-gray-700 border border-gray-300 rounded-md focus:outline-none focus:ring-emerald-600 focus:border-emerald-600">
                    <option value="">اختر نوع المادة</option>
                    <option value="تجريبية">تجريبية</option>
                    <option value="ثابتة">ثابتة</option>
                </select>
            </div>
            <button type="submit" class="py-2 px-4 bg-emerald-600 text-white rounded-md hover:bg-emerald-700 focus:outline-none focus:ring-emerald-600 focus:border-emerald-600">إضافة</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#create-form').submit(function(e) {
                e.preventDefault();
                $.ajax({
                    type: 'POST',
                    url: '../backend/مادة-تجريبية.php',
                    data: $(this).serialize(),
                    success: function(data) {
                        window.location.href = 'list_<?php echo $mod_slug; ?>.php';
                    }
                });
            });
        });
    </script>
</body>
</html>