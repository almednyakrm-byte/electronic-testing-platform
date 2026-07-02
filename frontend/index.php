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
        .glassmorphism {
            background-color: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px);
            border-radius: 10px;
            padding: 20px;
        }
    </style>
</head>
<body>
    <div class="flex justify-between items-center p-4 bg-emerald-600 text-white">
        <h1 class="text-3xl font-bold">منصة اختبارات إلكترونية مع تصحيح تلقائي</h1>
        <button class="bg-teal-500 hover:bg-teal-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='logout.php'">تسجيل خروج</button>
    </div>
    <div class="flex justify-center items-center p-4">
        <div class="glassmorphism w-1/2 p-4">
            <h1 class="text-2xl font-bold text-emerald-600">مرحباً بكم في منصة اختبارات إلكترونية مع تصحيح تلقائي</h1>
            <div class="flex justify-between items-center p-4">
                <div class="w-1/2">
                    <h2 class="text-lg font-bold text-teal-500">إحصائيات</h2>
                    <div id="stats-grid" class="grid grid-cols-1 gap-4 p-4"></div>
                </div>
                <div class="w-1/2">
                    <h2 class="text-lg font-bold text-teal-500">روابط سريعة</h2>
                    <ul class="list-none p-4">
                        <li class="py-2"><a href="#" class="text-emerald-600 hover:text-emerald-900">مقررات</a></li>
                        <li class="py-2"><a href="#" class="text-emerald-600 hover:text-emerald-900">طلاب</a></li>
                        <li class="py-2"><a href="#" class="text-emerald-600 hover:text-emerald-900">مدرسون</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <script>
        fetch('/api/stats')
            .then(response => response.json())
            .then(data => {
                const statsGrid = document.getElementById('stats-grid');
                data.forEach(stat => {
                    const statElement = document.createElement('div');
                    statElement.classList.add('bg-white', 'p-4', 'rounded', 'shadow-md');
                    statElement.innerHTML = `
                        <h3 class="text-lg font-bold">${stat.title}</h3>
                        <p class="text-gray-600">${stat.value}</p>
                    `;
                    statsGrid.appendChild(statElement);
                });
            })
            .catch(error => console.error(error));
    </script>
</body>
</html>


This code uses Tailwind CSS for styling and makes API calls to fetch stats dynamically. The color palette is set to emerald-600 and teal-500 as required. The dashboard layout includes a welcome message, logout button, overview stats grid, and quick links to manage modules.