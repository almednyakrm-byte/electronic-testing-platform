<?php
session_start();

// Check if user is authenticated
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>منصة اختبارات إلكترونية مع تصحيح تلقائي</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        .glassmorphism-card {
            background-color: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px);
            border-radius: 10px;
            padding: 20px;
        }
    </style>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto p-4">
        <div class="flex justify-between items-center mb-4">
            <h1 class="text-3xl font-bold text-emerald-600">منصة اختبارات إلكترونية مع تصحيح تلقائي</h1>
            <button class="bg-teal-500 hover:bg-teal-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='logout.php'">تسجيل خروج</button>
        </div>
        <div class="glassmorphism-card p-4 mb-4">
            <h2 class="text-2xl font-bold text-emerald-600">مرحباً بكم</h2>
            <p>منصة اختبارات إلكترونية مع تصحيح تلقائي</p>
        </div>
        <div class="glassmorphism-card p-4 mb-4">
            <h2 class="text-2xl font-bold text-emerald-600">إحصائيات</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <div class="bg-white rounded-lg shadow-md p-4">
                    <h3 class="text-lg font-bold text-emerald-600">عدد المستخدمين</h3>
                    <p id="users-count"></p>
                </div>
                <div class="bg-white rounded-lg shadow-md p-4">
                    <h3 class="text-lg font-bold text-emerald-600">عدد المستخدمين الراغبين</h3>
                    <p id="users-waiting-count"></p>
                </div>
                <div class="bg-white rounded-lg shadow-md p-4">
                    <h3 class="text-lg font-bold text-emerald-600">عدد الاختبارات</h3>
                    <p id="exams-count"></p>
                </div>
            </div>
        </div>
        <div class="glassmorphism-card p-4 mb-4">
            <h2 class="text-2xl font-bold text-emerald-600">روابط سريعة</h2>
            <ul class="list-none p-0 m-0">
                <li class="mb-2">
                    <a href="manage-users.php" class="text-emerald-600 hover:text-teal-500">مستخدمون</a>
                </li>
                <li class="mb-2">
                    <a href="manage-users-waiting.php" class="text-emerald-600 hover:text-teal-500">مستخدمون راغبون</a>
                </li>
                <li class="mb-2">
                    <a href="manage-exams.php" class="text-emerald-600 hover:text-teal-500">اختبارات</a>
                </li>
            </ul>
        </div>
    </div>

    <script>
        // Fetch stats dynamically via Javascript API calls from the backend files
        fetch('/api/stats')
            .then(response => response.json())
            .then(data => {
                document.getElementById('users-count').innerHTML = data.usersCount;
                document.getElementById('users-waiting-count').innerHTML = data.usersWaitingCount;
                document.getElementById('exams-count').innerHTML = data.examsCount;
            })
            .catch(error => console.error(error));
    </script>
</body>
</html>


This code uses Tailwind CSS for styling and includes a session check to redirect to the login page if the user is not authenticated. It also includes a glassmorphism card layout with a premium design and uses the specified color palette. The stats are fetched dynamically via a Javascript API call from the backend files.