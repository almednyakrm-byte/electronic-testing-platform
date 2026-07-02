**list_مستخدم.php**

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
    <title>مستخدم</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f7f7f7;
        }
        .header {
            background-color: #2f6f7f;
            padding: 1rem;
            text-align: center;
        }
        .header a {
            color: #fff;
            text-decoration: none;
        }
        .header a:hover {
            color: #ccc;
        }
        .container {
            max-width: 800px;
            margin: 2rem auto;
            padding: 2rem;
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .table {
            width: 100%;
            border-collapse: collapse;
        }
        .table th, .table td {
            border: 1px solid #ddd;
            padding: 1rem;
            text-align: left;
        }
        .table th {
            background-color: #f0f0f0;
        }
        .actions {
            text-align: right;
        }
        .actions a {
            margin-left: 1rem;
        }
        .search {
            padding: 1rem;
            border: 1px solid #ccc;
            border-radius: 10px;
            width: 100%;
        }
    </style>
</head>
<body>
    <div class="header">
        <a href="index.php">الرئيسية</a>
        <span class="text-lg font-bold">مستخدم</span>
        <a href="logout.php">تسجيل الخروج</a>
        <span class="text-lg font-bold">اسم المستخدم: <?php echo $_SESSION['username']; ?></span>
    </div>
    <div class="container">
        <h2 class="text-lg font-bold">قائمة المستخدمين</h2>
        <button class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='create_مستخدم.php'">إضافة مستخدم جديد</button>
        <div class="search">
            <input type="search" id="search" placeholder="بحث...">
            <button class="bg-teal-500 hover:bg-teal-700 text-white font-bold py-2 px-4 rounded" onclick="searchRecords()">بحث</button>
        </div>
        <table class="table">
            <thead>
                <tr>
                    <th>اسم المستخدم</th>
                    <th>البريد الإلكتروني</th>
                    <th>العمليات</th>
                </tr>
            </thead>
            <tbody id="records">
                <?php
                // Fetch records from backend
                $records = json_decode(file_get_contents('../backend/مستخدم.php'), true);
                foreach ($records as $record) {
                    echo '<tr>';
                    echo '<td>' . $record['اسم المستخدم'] . '</td>';
                    echo '<td>' . $record['البريد الإلكتروني'] . '</td>';
                    echo '<td class="actions">';
                    echo '<a href="edit_مستخدم.php?id=' . $record['id'] . '">تعديل</a>';
                    echo '<button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="deleteRecord(' . $record['id'] . ')">حذف</button>';
                    echo '</td>';
                    echo '</tr>';
                }
                ?>
            </tbody>
        </table>
    </div>
    <script>
        function searchRecords() {
            const search = document.getElementById('search').value;
            fetch('../backend/مستخدم.php?search=' + search)
                .then(response => response.json())
                .then(records => {
                    const recordsElement = document.getElementById('records');
                    recordsElement.innerHTML = '';
                    records.forEach(record => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td>${record['اسم المستخدم']}</td>
                            <td>${record['البريد الإلكتروني']}</td>
                            <td class="actions">
                                <a href="edit_مستخدم.php?id=${record['id']}">تعديل</a>
                                <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="deleteRecord(${record['id']})">حذف</button>
                            </td>
                        `;
                        recordsElement.appendChild(row);
                    });
                });
        }

        function deleteRecord(id) {
            if (confirm('هل تريد حذف المستخدم؟')) {
                fetch('../backend/مستخدم.php', {
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
                .catch(error => console.error(error));
            }
        }
    </script>
</body>
</html>

Note: This code assumes that the backend API is implemented to handle GET and DELETE requests for fetching and deleting records, respectively. The `../backend/مستخدم.php` file should return a JSON response containing the list of records or a success/failure message for deletion.