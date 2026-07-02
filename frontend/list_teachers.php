**list_teachers.php**

<?php
// Session validation
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teachers List</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
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
<body class="bg-gray-100">
    <div class="max-w-7xl mx-auto p-4">
        <nav class="bg-white shadow-sm p-4">
            <div class="flex justify-between">
                <a href="index.php" class="text-lg font-bold">Dashboard</a>
                <div class="flex items-center">
                    <p class="text-lg font-bold mr-4">Welcome, <?= $_SESSION['username'] ?></p>
                    <a href="logout.php" class="text-lg font-bold text-red-600 hover:text-red-800">Logout</a>
                </div>
            </div>
        </nav>
        <div class="p-4 bg-white shadow-sm mt-4">
            <h2 class="text-lg font-bold mb-4">Teachers List</h2>
            <button class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2 px-4 rounded mb-4" onclick="location.href='create_teachers.php'">Add New Item</button>
            <div class="flex justify-between mb-4">
                <input type="search" id="search" class="bg-gray-200 rounded w-full py-2 pl-10 text-sm focus:outline-none focus:ring focus:border-blue-500" placeholder="Search...">
                <button class="bg-teal-500 hover:bg-teal-700 text-white font-bold py-2 px-4 rounded" onclick="searchTeachers()">Search</button>
            </div>
            <table class="w-full border-collapse border border-gray-400">
                <thead>
                    <tr>
                        <th class="border border-gray-400 px-4 py-2">Name</th>
                        <th class="border border-gray-400 px-4 py-2">Email</th>
                        <th class="border border-gray-400 px-4 py-2">Actions</th>
                    </tr>
                </thead>
                <tbody id="teachers-list">
                    <?php
                    // Fetch data from backend
                    $response = file_get_contents('../backend/teachers.php');
                    $data = json_decode($response, true);
                    foreach ($data as $teacher) {
                        ?>
                        <tr>
                            <td class="border border-gray-400 px-4 py-2"><?= $teacher['name'] ?></td>
                            <td class="border border-gray-400 px-4 py-2"><?= $teacher['email'] ?></td>
                            <td class="border border-gray-400 px-4 py-2">
                                <a href="edit_teachers.php?id=<?= $teacher['id'] ?>" class="text-lg font-bold text-emerald-600 hover:text-emerald-800">Edit</a>
                                <button class="text-lg font-bold text-red-600 hover:text-red-800" onclick="deleteTeacher(<?= $teacher['id'] ?>)">Delete</button>
                            </td>
                        </tr>
                        <?php
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <script>
        function searchTeachers() {
            const searchInput = document.getElementById('search');
            const searchValue = searchInput.value.trim();
            if (searchValue) {
                fetch('../backend/teachers.php?search=' + searchValue)
                    .then(response => response.json())
                    .then(data => {
                        const teachersList = document.getElementById('teachers-list');
                        teachersList.innerHTML = '';
                        data.forEach(teacher => {
                            const row = document.createElement('tr');
                            row.innerHTML = `
                                <td class="border border-gray-400 px-4 py-2">${teacher.name}</td>
                                <td class="border border-gray-400 px-4 py-2">${teacher.email}</td>
                                <td class="border border-gray-400 px-4 py-2">
                                    <a href="edit_teachers.php?id=${teacher.id}" class="text-lg font-bold text-emerald-600 hover:text-emerald-800">Edit</a>
                                    <button class="text-lg font-bold text-red-600 hover:text-red-800" onclick="deleteTeacher(${teacher.id})">Delete</button>
                                </td>
                            `;
                            teachersList.appendChild(row);
                        });
                    });
            } else {
                fetch('../backend/teachers.php')
                    .then(response => response.json())
                    .then(data => {
                        const teachersList = document.getElementById('teachers-list');
                        teachersList.innerHTML = '';
                        data.forEach(teacher => {
                            const row = document.createElement('tr');
                            row.innerHTML = `
                                <td class="border border-gray-400 px-4 py-2">${teacher.name}</td>
                                <td class="border border-gray-400 px-4 py-2">${teacher.email}</td>
                                <td class="border border-gray-400 px-4 py-2">
                                    <a href="edit_teachers.php?id=${teacher.id}" class="text-lg font-bold text-emerald-600 hover:text-emerald-800">Edit</a>
                                    <button class="text-lg font-bold text-red-600 hover:text-red-800" onclick="deleteTeacher(${teacher.id})">Delete</button>
                                </td>
                            `;
                            teachersList.appendChild(row);
                        });
                    });
            }
        }

        function deleteTeacher(id) {
            if (confirm('Are you sure you want to delete this teacher?')) {
                fetch('../backend/teachers.php', {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ id: id })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Teacher deleted successfully!');
                        window.location.reload();
                    } else {
                        alert('Error deleting teacher!');
                    }
                })
                .catch(error => console.error(error));
            }
        }
    </script>
</body>
</html>

This code includes a premium Tailwind UI design with the specified color palette, session validation, and a list of teachers with actions to edit and delete. The search bar filters elements in real-time using the `searchTeachers()` function, and the `deleteTeacher()` function handles AJAX requests to delete teachers.