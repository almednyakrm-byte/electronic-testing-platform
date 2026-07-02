**list_students.php**

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
    <title>Students List</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        .emerald-600 {
            color: #008E73;
        }
        .teal-500 {
            color: #0097A7;
        }
    </style>
</head>
<body class="bg-gray-100">
    <header class="bg-white shadow-md p-4">
        <nav class="flex justify-between items-center">
            <a href="index.php" class="text-lg font-bold">Dashboard</a>
            <div class="flex items-center">
                <p class="mr-2">Welcome, <?= $_SESSION['username'] ?></p>
                <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='logout.php'">Logout</button>
            </div>
        </nav>
    </header>
    <main class="max-w-7xl mx-auto p-4">
        <h1 class="text-3xl font-bold mb-4">Students List</h1>
        <button class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='create_students.php'">Add New Item</button>
        <div class="flex justify-between items-center mb-4">
            <input type="search" id="search" class="w-full p-2 pl-10 text-sm text-gray-700 bg-gray-100 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600" placeholder="Search...">
            <button class="bg-teal-500 hover:bg-teal-700 text-white font-bold py-2 px-4 rounded" onclick="searchStudents()">Search</button>
        </div>
        <table class="w-full border-collapse border border-gray-300">
            <thead>
                <tr>
                    <th class="px-4 py-2">Name</th>
                    <th class="px-4 py-2">Email</th>
                    <th class="px-4 py-2">Actions</th>
                </tr>
            </thead>
            <tbody id="students-list">
                <!-- List of students will be fetched here -->
            </tbody>
        </table>
    </main>

    <script>
        // Fetch list of students
        fetch('../backend/students.php')
            .then(response => response.json())
            .then(data => {
                const studentsList = document.getElementById('students-list');
                data.forEach(student => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td class="px-4 py-2">${student.name}</td>
                        <td class="px-4 py-2">${student.email}</td>
                        <td class="px-4 py-2">
                            <a href="edit_students.php?id=${student.id}" class="text-blue-500 hover:text-blue-700">Edit</a>
                            <button class="text-red-500 hover:text-red-700" onclick="deleteStudent(${student.id})">Delete</button>
                        </td>
                    `;
                    studentsList.appendChild(row);
                });
            })
            .catch(error => console.error(error));

        // Search students
        function searchStudents() {
            const searchInput = document.getElementById('search');
            const searchQuery = searchInput.value.trim();
            if (searchQuery) {
                fetch('../backend/students.php?search=' + searchQuery)
                    .then(response => response.json())
                    .then(data => {
                        const studentsList = document.getElementById('students-list');
                        studentsList.innerHTML = '';
                        data.forEach(student => {
                            const row = document.createElement('tr');
                            row.innerHTML = `
                                <td class="px-4 py-2">${student.name}</td>
                                <td class="px-4 py-2">${student.email}</td>
                                <td class="px-4 py-2">
                                    <a href="edit_students.php?id=${student.id}" class="text-blue-500 hover:text-blue-700">Edit</a>
                                    <button class="text-red-500 hover:text-red-700" onclick="deleteStudent(${student.id})">Delete</button>
                                </td>
                            `;
                            studentsList.appendChild(row);
                        });
                    })
                    .catch(error => console.error(error));
            } else {
                fetch('../backend/students.php')
                    .then(response => response.json())
                    .then(data => {
                        const studentsList = document.getElementById('students-list');
                        studentsList.innerHTML = '';
                        data.forEach(student => {
                            const row = document.createElement('tr');
                            row.innerHTML = `
                                <td class="px-4 py-2">${student.name}</td>
                                <td class="px-4 py-2">${student.email}</td>
                                <td class="px-4 py-2">
                                    <a href="edit_students.php?id=${student.id}" class="text-blue-500 hover:text-blue-700">Edit</a>
                                    <button class="text-red-500 hover:text-red-700" onclick="deleteStudent(${student.id})">Delete</button>
                                </td>
                            `;
                            studentsList.appendChild(row);
                        });
                    })
                    .catch(error => console.error(error));
            }
        }

        // Delete student
        function deleteStudent(id) {
            if (confirm('Are you sure you want to delete this student?')) {
                fetch('../backend/students.php', {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ id: id })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Student deleted successfully!');
                        location.reload();
                    } else {
                        alert('Error deleting student!');
                    }
                })
                .catch(error => console.error(error));
            }
        }
    </script>
</body>
</html>

**students.php (backend)**

<?php
// Database connection
$conn = mysqli_connect('localhost', 'username', 'password', 'database');

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Get list of students
$query = "SELECT * FROM students";
$result = mysqli_query($conn, $query);

// Fetch data
$data = array();
while ($row = mysqli_fetch_assoc($result)) {
    $data[] = $row;
}

// Search students
if (isset($_GET['search'])) {
    $searchQuery = $_GET['search'];
    $query = "SELECT * FROM students WHERE name LIKE '%$searchQuery%' OR email LIKE '%$searchQuery%'";
    $result = mysqli_query($conn, $query);
    $data = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = $row;
    }
}

// Delete student
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $query = "DELETE FROM students WHERE id = '$id'";
    mysqli_query($conn, $query);
    echo json_encode(array('success' => true));
}

// Close connection
mysqli_close($conn);
?>

Note: This code assumes you have a `students` table in your database with columns `id`, `name`, and `email`. You'll need to modify the code to match your actual database schema. Additionally, this code uses a simple search query that may not be efficient for large datasets. You may want to consider using a more robust search solution.