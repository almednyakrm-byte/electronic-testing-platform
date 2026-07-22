**list_مستخدمون-راغبون.php**

<?php
// Session validation
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
    <title>مستخدمون راغبون</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            direction: rtl;
        }
        .table {
            direction: rtl;
        }
        .table th, .table td {
            text-align: right;
        }
    </style>
</head>
<body class="bg-gray-100">
    <header class="bg-emerald-600 py-4">
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-center">
                <a href="index.php" class="text-white hover:text-teal-500">الرئيسية</a>
                <div class="flex items-center">
                    <span class="text-white">مرحباً <?= $_SESSION['username'] ?></span>
                    <button class="bg-teal-500 hover:bg-teal-700 text-white font-bold py-2 px-4 rounded ml-4" onclick="document.location='logout.php'">تسجيل الخروج</button>
                </div>
            </div>
        </div>
    </header>
    <main class="container mx-auto px-4 pt-4">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-2xl text-emerald-600">مستخدمون راغبون</h2>
            <button class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2 px-4 rounded" onclick="document.location='create_مستخدمون-راغبون.php'">إضافة مستخدم جديد</button>
        </div>
        <div class="flex justify-between items-center mb-4">
            <input type="search" id="search" class="w-full py-2 pl-10 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-emerald-600" placeholder="بحث...">
            <button class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2 px-4 rounded" onclick="searchRecords()">بحث</button>
        </div>
        <table class="table w-full">
            <thead>
                <tr>
                    <th>اسم المستخدم</th>
                    <th>البريد الإلكتروني</th>
                    <th>الجوال</th>
                    <th>الإجراءات</th>
                </tr>
            </thead>
            <tbody id="records">
                <!-- Records will be loaded here -->
            </tbody>
        </table>
    </main>
    <script>
        // Search function
        function searchRecords() {
            const searchValue = document.getElementById('search').value;
            fetch('../backend/مستخدمون-راغبون.php?search=' + searchValue)
                .then(response => response.json())
                .then(data => {
                    const records = document.getElementById('records');
                    records.innerHTML = '';
                    data.forEach(record => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td>${record.اسم_المستخدم}</td>
                            <td>${record.البريد_الإلكتروني}</td>
                            <td>${record.الجوال}</td>
                            <td>
                                <a href="edit_مستخدمون-راغبون.php?id=${record.id}" class="text-emerald-600 hover:text-teal-500">تعديل</a>
                                <button class="text-red-600 hover:text-red-800" onclick="deleteRecord(${record.id})">حذف</button>
                            </td>
                        `;
                        records.appendChild(row);
                    });
                });
        }

        // Delete record function
        function deleteRecord(id) {
            if (confirm('هل تريد حذف هذا المستخدم؟')) {
                fetch('../backend/مستخدمون-راغبون.php', {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ id: id })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        searchRecords();
                    } else {
                        alert(data.message);
                    }
                });
            }
        }

        // Load records on page load
        fetch('../backend/مستخدمون-راغبون.php')
            .then(response => response.json())
            .then(data => {
                const records = document.getElementById('records');
                data.forEach(record => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>${record.اسم_المستخدم}</td>
                        <td>${record.البريد_الإلكتروني}</td>
                        <td>${record.الجوال}</td>
                        <td>
                            <a href="edit_مستخدمون-راغبون.php?id=${record.id}" class="text-emerald-600 hover:text-teal-500">تعديل</a>
                            <button class="text-red-600 hover:text-red-800" onclick="deleteRecord(${record.id})">حذف</button>
                        </td>
                    `;
                    records.appendChild(row);
                });
            });
    </script>
</body>
</html>

This code uses the Fetch API to load records from the backend and delete records using AJAX requests. It also includes a search function that filters records in real-time. The UI is built using Tailwind CSS with a custom color palette matching the theme.