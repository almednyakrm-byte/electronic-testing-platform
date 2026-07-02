**list_مدرسون.php**

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
    <title>مدرسون</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f7f7f7;
        }
        .bg-emerald-600 {
            background-color: #0d9488;
        }
        .text-teal-500 {
            color: #0097a7;
        }
        .table {
            border-collapse: collapse;
            width: 100%;
        }
        .table th, .table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        .table th {
            background-color: #f0f0f0;
        }
    </style>
</head>
<body class="bg-emerald-600">
    <div class="container mx-auto p-4 mt-4 bg-white rounded-md shadow-md">
        <div class="flex justify-between items-center mb-4">
            <a href="index.php" class="text-teal-500 hover:text-teal-700">الرئيسية</a>
            <div class="flex items-center">
                <span class="text-teal-500 mr-2">مرحباً, <?php echo $_SESSION['username']; ?></span>
                <a href="logout.php" class="text-teal-500 hover:text-teal-700">تسجيل الخروج</a>
            </div>
        </div>
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-lg text-teal-500">مدرسون</h2>
            <a href="create_مدرسون.php" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2 px-4 rounded">إضافة جديد</a>
        </div>
        <div class="flex justify-between items-center mb-4">
            <input type="search" id="search" class="w-full p-2 pl-10 text-sm text-gray-700 bg-gray-100 rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-600" placeholder="بحث...">
            <button id="search-btn" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2 px-4 rounded">بحث</button>
        </div>
        <table class="table">
            <thead>
                <tr>
                    <th>اسم المدرس</th>
                    <th>البريد الإلكتروني</th>
                    <th>تليفون</th>
                    <th>حذف</th>
                    <th>تعديل</th>
                </tr>
            </thead>
            <tbody id="records">
                <!-- Records will be loaded here -->
            </tbody>
        </table>
    </div>

    <script>
        const searchInput = document.getElementById('search');
        const searchBtn = document.getElementById('search-btn');
        const recordsTable = document.getElementById('records');

        searchBtn.addEventListener('click', () => {
            const searchTerm = searchInput.value.trim();
            fetch('../backend/مدرسون.php?search=' + searchTerm)
                .then(response => response.json())
                .then(data => {
                    const records = data.records;
                    recordsTable.innerHTML = '';
                    records.forEach(record => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td>${record.اسم_المدرس}</td>
                            <td>${record.البريد_الإلكتروني}</td>
                            <td>${record.تليفون}</td>
                            <td>
                                <button class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="deleteRecord(${record.id})">حذف</button>
                            </td>
                            <td>
                                <a href="edit_مدرسون.php?id=${record.id}" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2 px-4 rounded">تعديل</a>
                            </td>
                        `;
                        recordsTable.appendChild(row);
                    });
                });
        });

        function deleteRecord(id) {
            if (confirm('هل تريد حذف هذا السجل؟')) {
                fetch('../backend/مدرسون.php', {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ id: id })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('تم حذف السجل بنجاح');
                        window.location.reload();
                    } else {
                        alert('حدث خطأ أثناء حذف السجل');
                    }
                });
            }
        }

        fetch('../backend/مدرسون.php')
            .then(response => response.json())
            .then(data => {
                const records = data.records;
                records.forEach(record => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>${record.اسم_المدرس}</td>
                        <td>${record.البريد_الإلكتروني}</td>
                        <td>${record.تليفون}</td>
                        <td>
                            <button class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="deleteRecord(${record.id})">حذف</button>
                        </td>
                        <td>
                            <a href="edit_مدرسون.php?id=${record.id}" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2 px-4 rounded">تعديل</a>
                        </td>
                    `;
                    recordsTable.appendChild(row);
                });
            });
    </script>
</body>
</html>

**Note:** This code assumes that you have a backend PHP file `مدرسون.php` that handles the GET and DELETE requests for the records. The `مدرسون.php` file should return a JSON response with the records or an error message.