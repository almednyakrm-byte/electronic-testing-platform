**list_مادة.php**

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
    <title>مادة</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f7f7f7;
        }
        .emerald-600 {
            color: #008E77;
        }
        .teal-500 {
            color: #0097A7;
        }
    </style>
</head>
<body>
    <div class="container mx-auto p-4 mt-4 bg-white rounded-lg shadow-md">
        <header class="flex justify-between items-center mb-4">
            <a href="index.php" class="text-lg font-bold text-emerald-600 hover:text-teal-500">الرئيسية</a>
            <div class="flex items-center">
                <p class="mr-2 text-lg font-bold text-teal-500"><?= $_SESSION['username']; ?></p>
                <a href="logout.php" class="text-lg font-bold text-emerald-600 hover:text-teal-500">تسجيل خروج</a>
            </div>
        </header>
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-lg font-bold text-emerald-600">قائمة المادة</h2>
            <a href="create_مادة.php" class="bg-emerald-600 hover:bg-teal-500 text-white font-bold py-2 px-4 rounded">إضافة جديد</a>
        </div>
        <div class="flex justify-between items-center mb-4">
            <input type="search" id="search" class="w-full p-2 pl-10 text-sm text-gray-700 placeholder-gray-600 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-600" placeholder="بحث...">
            <button id="search-btn" class="bg-emerald-600 hover:bg-teal-500 text-white font-bold py-2 px-4 rounded">بحث</button>
        </div>
        <table class="w-full border-collapse border border-gray-300">
            <thead>
                <tr>
                    <th class="px-4 py-2 border border-gray-300">المادة</th>
                    <th class="px-4 py-2 border border-gray-300">الإجراءات</th>
                </tr>
            </thead>
            <tbody id="records">
                <?php
                // Fetch records from backend
                $response = file_get_contents('../backend/مادة.php');
                $records = json_decode($response, true);
                foreach ($records as $record) {
                    ?>
                    <tr>
                        <td class="px-4 py-2 border border-gray-300"><?= $record['name']; ?></td>
                        <td class="px-4 py-2 border border-gray-300">
                            <a href="edit_مادة.php?id=<?= $record['id']; ?>" class="text-emerald-600 hover:text-teal-500">تعديل</a>
                            <button class="bg-teal-500 hover:bg-emerald-600 text-white font-bold py-2 px-4 rounded" onclick="deleteRecord(<?= $record['id']; ?>)">حذف</button>
                        </td>
                    </tr>
                    <?php
                }
                ?>
            </tbody>
        </table>
    </div>

    <script>
        // Search functionality
        const searchInput = document.getElementById('search');
        const searchBtn = document.getElementById('search-btn');
        searchBtn.addEventListener('click', () => {
            const searchTerm = searchInput.value;
            fetch('../backend/مادة.php?search=' + searchTerm)
                .then(response => response.json())
                .then(data => {
                    const records = document.getElementById('records');
                    records.innerHTML = '';
                    data.forEach(record => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td class="px-4 py-2 border border-gray-300">${record.name}</td>
                            <td class="px-4 py-2 border border-gray-300">
                                <a href="edit_مادة.php?id=${record.id}" class="text-emerald-600 hover:text-teal-500">تعديل</a>
                                <button class="bg-teal-500 hover:bg-emerald-600 text-white font-bold py-2 px-4 rounded" onclick="deleteRecord(${record.id})">حذف</button>
                            </td>
                        `;
                        records.appendChild(row);
                    });
                });
        });

        // Delete record functionality
        function deleteRecord(id) {
            if (confirm('هل أنت متأكد من حذف المادة؟')) {
                fetch('../backend/مادة.php', {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ id: id })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('تم حذف المادة بنجاح!');
                        window.location.reload();
                    } else {
                        alert('حدث خطأ أثناء حذف المادة!');
                    }
                })
                .catch(error => console.error('Error:', error));
            }
        }
    </script>
</body>
</html>

This code includes the following features:

1. Session validation: Redirects to login.php if the user is not authenticated.
2. Header navigation: Links to index.php, current user info, and logout.
3. Table showing list of records: Includes actions for editing and deleting records.
4. 'Add New Item' button: Links to create_مادة.php.
5. Search bar: Filters elements in real-time using AJAX.
6. AJAX Javascript: Fetches list records from '../backend/مادة.php' (GET) and DELETE requests.

Note: This code assumes that the backend API is implemented in a separate file (`../backend/مادة.php`) and returns JSON data.