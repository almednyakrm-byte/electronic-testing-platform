**list_مستخدمون.php**

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
    <title>مستخدمون</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        .emerald-600 {
            color: #008E77;
        }
        .teal-500 {
            color: #0097A7;
        }
    </style>
</head>
<body class="bg-gray-100">
    <header class="bg-white shadow-md p-4">
        <nav class="flex justify-between items-center">
            <a href="index.php" class="text-lg font-bold">الرئيسية</a>
            <div class="flex items-center">
                <p class="mr-2">مرحباً, <?= $_SESSION['username'] ?></p>
                <a href="logout.php" class="text-red-600 hover:text-red-800">تسجيل الخروج</a>
            </div>
        </nav>
    </header>
    <main class="max-w-7xl mx-auto p-4">
        <h1 class="text-3xl font-bold mb-4">قائمة المستخدمين</h1>
        <div class="flex justify-between items-center mb-4">
            <button class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='create_مستخدمون.php'">إضافة مستخدم جديد</button>
            <input type="search" class="w-full p-2 pl-10 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-600" placeholder="بحث" id="search" onkeyup="filterList()">
        </div>
        <table class="w-full table-auto">
            <thead class="bg-gray-200">
                <tr>
                    <th class="px-4 py-2">اسم المستخدم</th>
                    <th class="px-4 py-2">البريد الإلكتروني</th>
                    <th class="px-4 py-2">العمليات</th>
                </tr>
            </thead>
            <tbody id="list">
                <?php
                // Fetch list records from backend
                $response = file_get_contents('../backend/مستخدمون.php');
                $users = json_decode($response, true);
                foreach ($users as $user) {
                    ?>
                    <tr>
                        <td class="px-4 py-2"><?= $user['name'] ?></td>
                        <td class="px-4 py-2"><?= $user['email'] ?></td>
                        <td class="px-4 py-2">
                            <a href="edit_مستخدمون.php?id=<?= $user['id'] ?>" class="text-teal-500 hover:text-teal-700">تعديل</a>
                            <button class="ml-2 text-red-600 hover:text-red-800" onclick="deleteUser(<?= $user['id'] ?>)">حذف</button>
                        </td>
                    </tr>
                    <?php
                }
                ?>
            </tbody>
        </table>
    </main>

    <script>
        function filterList() {
            const search = document.getElementById('search').value.toLowerCase();
            const list = document.getElementById('list');
            const rows = list.getElementsByTagName('tr');
            for (let i = 0; i < rows.length; i++) {
                const row = rows[i];
                const cells = row.getElementsByTagName('td');
                let match = false;
                for (let j = 0; j < cells.length; j++) {
                    const cell = cells[j];
                    if (cell.textContent.toLowerCase().includes(search)) {
                        match = true;
                        break;
                    }
                }
                if (match) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            }
        }

        function deleteUser(id) {
            if (confirm('هل تريد حذف المستخدم؟')) {
                fetch('../backend/مستخدمون.php', {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ id: id })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('تم حذف المستخدم بنجاح');
                        location.reload();
                    } else {
                        alert('حدث خطأ أثناء حذف المستخدم');
                    }
                })
                .catch(error => console.error('Error:', error));
            }
        }
    </script>
</body>
</html>

This code includes a premium Tailwind UI layout with a custom color palette, session validation, and a list of users with actions to edit and delete. The list is filtered in real-time using a search bar, and the delete action uses an AJAX call to the backend.