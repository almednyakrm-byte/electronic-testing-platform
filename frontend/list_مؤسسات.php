**list_مؤسسات.php**

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
    <title>مؤسسات</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f7f7f7;
        }
        .header {
            background-color: #2c3e50;
            color: #fff;
            padding: 1rem;
            text-align: center;
        }
        .header .logo {
            font-size: 1.5rem;
            font-weight: bold;
            margin-right: 1rem;
        }
        .header .nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .header .nav .links {
            display: flex;
            gap: 1rem;
        }
        .header .nav .links a {
            color: #fff;
            text-decoration: none;
        }
        .header .nav .links a:hover {
            color: #ccc;
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
            background-color: #2c3e50;
            color: #fff;
        }
        .table td {
            background-color: #f7f7f7;
        }
        .table td a {
            text-decoration: none;
            color: #2c3e50;
        }
        .table td a:hover {
            color: #ccc;
        }
        .search-bar {
            width: 100%;
            padding: 1rem;
            border: 1px solid #ddd;
            border-radius: 0.5rem;
            background-color: #f7f7f7;
        }
        .search-bar input[type="search"] {
            width: 100%;
            padding: 1rem;
            border: none;
            border-radius: 0.5rem;
            background-color: #f7f7f7;
        }
        .search-bar input[type="search"]:focus {
            outline: none;
            border-color: #2c3e50;
        }
        .add-new-item {
            background-color: #2c3e50;
            color: #fff;
            padding: 1rem 2rem;
            border: none;
            border-radius: 0.5rem;
            cursor: pointer;
        }
        .add-new-item:hover {
            background-color: #2c3e50;
            color: #fff;
        }
    </style>
</head>
<body>
    <header class="header">
        <div class="logo">مؤسسات</div>
        <div class="nav">
            <div class="links">
                <a href="index.php">الرئيسية</a>
                <a href="profile.php">الملف الشخصي</a>
                <a href="logout.php">تسجيل الخروج</a>
            </div>
            <div class="user-info">
                <span>مرحباً, <?php echo $_SESSION['username']; ?></span>
            </div>
        </div>
    </header>
    <main>
        <div class="container">
            <div class="search-bar">
                <input type="search" id="search-input" placeholder="بحث...">
                <button class="add-new-item" onclick="searchRecords()">بحث</button>
            </div>
            <table class="table">
                <thead>
                    <tr>
                        <th>اسم المؤسسة</th>
                        <th>العنوان</th>
                        <th>التليفون</th>
                        <th>الإجراءات</th>
                    </tr>
                </thead>
                <tbody id="records-table">
                    <?php
                    // Fetch records from backend
                    $records = json_decode(file_get_contents('../backend/مؤسسات.php'), true);
                    foreach ($records as $record) {
                        ?>
                        <tr>
                            <td><?php echo $record['اسم المؤسسة']; ?></td>
                            <td><?php echo $record['العنوان']; ?></td>
                            <td><?php echo $record['التليفون']; ?></td>
                            <td>
                                <a href="edit_مؤسسات.php?id=<?php echo $record['id']; ?>">تعديل</a>
                                <button class="delete-record" data-id="<?php echo $record['id']; ?>">حذف</button>
                            </td>
                        </tr>
                        <?php
                    }
                    ?>
                </tbody>
            </table>
            <button class="add-new-item" onclick="location.href='create_مؤسسات.php'">إضافة جديد</button>
        </div>
    </main>
    <script>
        // Search records
        function searchRecords() {
            const searchInput = document.getElementById('search-input');
            const searchQuery = searchInput.value.trim();
            if (searchQuery) {
                fetch('../backend/مؤسسات.php?search=' + searchQuery)
                    .then(response => response.json())
                    .then(records => {
                        const recordsTable = document.getElementById('records-table');
                        recordsTable.innerHTML = '';
                        records.forEach(record => {
                            const row = document.createElement('tr');
                            row.innerHTML = `
                                <td>${record['اسم المؤسسة']}</td>
                                <td>${record['العنوان']}</td>
                                <td>${record['التليفون']}</td>
                                <td>
                                    <a href="edit_مؤسسات.php?id=${record['id']}">تعديل</a>
                                    <button class="delete-record" data-id="${record['id']}">حذف</button>
                                </td>
                            `;
                            recordsTable.appendChild(row);
                        });
                    })
                    .catch(error => console.error(error));
            } else {
                fetch('../backend/مؤسسات.php')
                    .then(response => response.json())
                    .then(records => {
                        const recordsTable = document.getElementById('records-table');
                        recordsTable.innerHTML = '';
                        records.forEach(record => {
                            const row = document.createElement('tr');
                            row.innerHTML = `
                                <td>${record['اسم المؤسسة']}</td>
                                <td>${record['العنوان']}</td>
                                <td>${record['التليفون']}</td>
                                <td>
                                    <a href="edit_مؤسسات.php?id=${record['id']}">تعديل</a>
                                    <button class="delete-record" data-id="${record['id']}">حذف</button>
                                </td>
                            `;
                            recordsTable.appendChild(row);
                        });
                    })
                    .catch(error => console.error(error));
            }
        }

        // Delete record
        document.addEventListener('click', event => {
            if (event.target.classList.contains('delete-record')) {
                const recordId = event.target.dataset.id;
                fetch('../backend/مؤسسات.php', {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ id: recordId })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('تم حذف السجل بنجاح');
                        location.reload();
                    } else {
                        alert('حدث خطأ أثناء حذف السجل');
                    }
                })
                .catch(error => console.error(error));
            }
        });
    </script>
</body>
</html>

This code includes the following features:

1.  Session validation: Redirects to login.php if the user is not authenticated.
2.  Header navigation: Includes links to index.php, profile.php, and logout.php.
3.  Table showing list of records: Includes actions for editing and deleting records.
4.  Search bar: Filters records in real-time using the Fetch API.
5.  AJAX calls: Uses the Fetch API to fetch records from the backend and delete records.

Note that this code assumes that the backend API is implemented and returns the records in JSON format. You will need to modify the code to match your specific backend API implementation.