**list_مادة-فنية.php**

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
    <title>مادة فنية</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            direction: rtl;
        }
        .emerald-600 {
            color: #03DAC5;
        }
        .teal-500 {
            color: #0097A7;
        }
    </style>
</head>
<body class="bg-gray-100">
    <header class="bg-white shadow-md p-4">
        <nav class="flex justify-between items-center">
            <a href="index.php" class="text-lg font-bold">الصفحة الرئيسية</a>
            <div class="flex items-center">
                <p class="mr-2">مرحباً, <?php echo $_SESSION['username']; ?></p>
                <a href="logout.php" class="text-red-600 hover:text-red-800">تسجيل خروج</a>
            </div>
        </nav>
    </header>
    <main class="max-w-7xl mx-auto p-4">
        <h1 class="text-3xl font-bold mb-4">قائمة مادة فنية</h1>
        <div class="flex justify-between items-center mb-4">
            <button class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='create_مادة-فنية.php'">إضافة جديد</button>
            <input type="search" class="w-full p-2 pl-10 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-600" placeholder="بحث" id="search" oninput="filterList()">
        </div>
        <table class="w-full border-collapse border border-gray-400">
            <thead>
                <tr>
                    <th class="border border-gray-400 p-2">العنوان</th>
                    <th class="border border-gray-400 p-2">الوصف</th>
                    <th class="border border-gray-400 p-2">الإجراءات</th>
                </tr>
            </thead>
            <tbody id="list">
                <?php
                // Fetch list records from backend
                $url = '../backend/مادة-فنية.php';
                $response = fetch($url);
                $data = json_decode($response, true);
                foreach ($data as $item) {
                    ?>
                    <tr>
                        <td class="border border-gray-400 p-2"><?php echo $item['title']; ?></td>
                        <td class="border border-gray-400 p-2"><?php echo $item['description']; ?></td>
                        <td class="border border-gray-400 p-2">
                            <a href="edit_مادة-فنية.php?id=<?php echo $item['id']; ?>" class="text-teal-500 hover:text-teal-800">تعديل</a>
                            <button class="text-red-600 hover:text-red-800" onclick="deleteItem(<?php echo $item['id']; ?>)">حذف</button>
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
            const search = document.getElementById('search').value;
            const list = document.getElementById('list');
            const rows = list.getElementsByTagName('tr');
            for (let i = 0; i < rows.length; i++) {
                const cells = rows[i].getElementsByTagName('td');
                let match = false;
                for (let j = 0; j < cells.length; j++) {
                    if (cells[j].textContent.includes(search)) {
                        match = true;
                        break;
                    }
                }
                if (match) {
                    rows[i].style.display = '';
                } else {
                    rows[i].style.display = 'none';
                }
            }
        }

        function deleteItem(id) {
            if (confirm('هل أنت متأكد من حذف هذا العنصر؟')) {
                fetch('../backend/مادة-فنية.php', {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ id: id })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('تم حذف العنصر بنجاح');
                        location.reload();
                    } else {
                        alert('حدث خطأ أثناء حذف العنصر');
                    }
                })
                .catch(error => console.error('Error:', error));
            }
        }

        function fetch(url) {
            return fetch(url)
            .then(response => response.json())
            .catch(error => console.error('Error:', error));
        }
    </script>
</body>
</html>

Note: This code assumes that you have a backend script (`../backend/مادة-فنية.php`) that returns a JSON array of records. The `fetch` function is used to make GET and DELETE requests to the backend. The `deleteItem` function uses a confirmation dialog to ask the user if they are sure they want to delete the item. If the user confirms, the item is deleted and the list is reloaded.