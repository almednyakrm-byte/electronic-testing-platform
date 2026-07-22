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
        .bg-emerald-600 {
            background-color: #0d6efd;
        }
        .text-teal-500 {
            color: #0fc2c9;
        }
    </style>
</head>
<body>
    <div class="container mx-auto p-4">
        <div class="flex justify-between mb-4">
            <a href="index.php" class="text-lg font-bold text-emerald-600">Back to Dashboard</a>
            <div class="flex items-center">
                <p class="text-lg font-bold text-teal-500"><?= $_SESSION['username']; ?></p>
                <button class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2 px-4 rounded ml-4" onclick="location.href='logout.php'">Logout</button>
            </div>
        </div>
        <div class="bg-emerald-600 p-4 rounded mb-4">
            <h2 class="text-lg font-bold text-white">Teachers List</h2>
            <button class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2 px-4 rounded mb-4" onclick="location.href='create_teachers.php'">Add New Item</button>
            <div class="flex justify-between mb-4">
                <input type="search" id="search" class="w-full p-2 pl-10 text-sm text-gray-700 bg-gray-100 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600" placeholder="Search...">
                <button class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2 px-4 rounded" onclick="searchTeachers()">Search</button>
            </div>
            <table class="w-full border-collapse border border-gray-200">
                <thead>
                    <tr>
                        <th class="px-4 py-2">Name</th>
                        <th class="px-4 py-2">Email</th>
                        <th class="px-4 py-2">Actions</th>
                    </tr>
                </thead>
                <tbody id="teachers-list">
                    <!-- List of teachers will be rendered here -->
                </tbody>
            </table>
        </div>
    </div>

    <script>
        // Fetch API to get list of teachers
        async function getTeachers() {
            try {
                const response = await fetch('../backend/teachers.php');
                const data = await response.json();
                renderTeachers(data);
            } catch (error) {
                console.error(error);
            }
        }

        // Render list of teachers
        function renderTeachers(teachers) {
            const listElement = document.getElementById('teachers-list');
            listElement.innerHTML = '';
            teachers.forEach((teacher) => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td class="px-4 py-2">${teacher.name}</td>
                    <td class="px-4 py-2">${teacher.email}</td>
                    <td class="px-4 py-2">
                        <a href="edit_teachers.php?id=${teacher.id}" class="text-emerald-600 hover:text-emerald-700">Edit</a>
                        <button class="text-red-600 hover:text-red-700" onclick="deleteTeacher(${teacher.id})">Delete</button>
                    </td>
                `;
                listElement.appendChild(row);
            });
        }

        // Delete teacher using AJAX
        async function deleteTeacher(id) {
            try {
                const response = await fetch('../backend/teachers.php', {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ id })
                });
                if (response.ok) {
                    getTeachers();
                } else {
                    console.error('Error deleting teacher');
                }
            } catch (error) {
                console.error(error);
            }
        }

        // Search teachers
        function searchTeachers() {
            const searchInput = document.getElementById('search');
            const searchQuery = searchInput.value.trim();
            if (searchQuery) {
                fetch('../backend/teachers.php', {
                    method: 'GET',
                    params: { search: searchQuery }
                })
                .then(response => response.json())
                .then(data => renderTeachers(data))
                .catch(error => console.error(error));
            } else {
                getTeachers();
            }
        }

        // Initialize list of teachers
        getTeachers();
    </script>
</body>
</html>

**teachers.php (backend)**

<?php
// Database connection
$conn = new mysqli('localhost', 'username', 'password', 'database');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get list of teachers
if (isset($_GET['search'])) {
    $searchQuery = $_GET['search'];
    $query = "SELECT * FROM teachers WHERE name LIKE '%$searchQuery%' OR email LIKE '%$searchQuery%'";
} else {
    $query = "SELECT * FROM teachers";
}

$result = $conn->query($query);
$teachers = array();
while ($row = $result->fetch_assoc()) {
    $teachers[] = $row;
}

// Output list of teachers in JSON format
header('Content-Type: application/json');
echo json_encode($teachers);

// Delete teacher
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $query = "DELETE FROM teachers WHERE id = '$id'";
    $conn->query($query);
    echo json_encode(array('message' => 'Teacher deleted successfully'));
}

$conn->close();
?>