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
    <title>Students Management</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        /* Custom color palette */
        :root {
            --emerald-600: #0d9488;
            --teal-500: #0097a7;
        }
    </style>
</head>
<body class="bg-gray-100">
    <header class="bg-white shadow-md p-4">
        <nav class="flex justify-between items-center">
            <a href="index.php" class="text-lg font-bold text-emerald-600">Home</a>
            <div class="flex items-center">
                <p class="mr-2 text-gray-600">Welcome, <?= $_SESSION['username'] ?></p>
                <button class="bg-teal-500 hover:bg-teal-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='logout.php'">Logout</button>
            </div>
        </nav>
    </header>
    <main class="container mx-auto p-4">
        <h1 class="text-lg font-bold text-emerald-600 mb-4">Students Management</h1>
        <button class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='create_students.php'">Add New Item</button>
        <div class="flex justify-between items-center mb-4">
            <input type="search" class="w-full p-2 text-gray-600" placeholder="Search students" id="search-input">
            <button class="bg-teal-500 hover:bg-teal-700 text-white font-bold py-2 px-4 rounded" onclick="searchStudents()">Search</button>
        </div>
        <table class="w-full border-collapse border border-gray-400">
            <thead>
                <tr>
                    <th class="border border-gray-400 px-4 py-2">ID</th>
                    <th class="border border-gray-400 px-4 py-2">Name</th>
                    <th class="border border-gray-400 px-4 py-2">Action</th>
                </tr>
            </thead>
            <tbody id="students-list">
                <?php
                // Fetch students list from backend
                $students = json_decode(file_get_contents('../backend/students.php'), true);
                foreach ($students as $student) {
                    ?>
                    <tr>
                        <td class="border border-gray-400 px-4 py-2"><?= $student['id'] ?></td>
                        <td class="border border-gray-400 px-4 py-2"><?= $student['name'] ?></td>
                        <td class="border border-gray-400 px-4 py-2">
                            <a href="edit_students.php?id=<?= $student['id'] ?>" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2 px-4 rounded">Edit</a>
                            <button class="bg-teal-500 hover:bg-teal-700 text-white font-bold py-2 px-4 rounded" onclick="deleteStudent(<?= $student['id'] ?>)">Delete</button>
                        </td>
                    </tr>
                    <?php
                }
                ?>
            </tbody>
        </table>
    </main>

    <script>
        function searchStudents() {
            const searchInput = document.getElementById('search-input').value;
            fetch('../backend/students.php?search=' + searchInput)
                .then(response => response.json())
                .then(data => {
                    const studentsList = document.getElementById('students-list');
                    studentsList.innerHTML = '';
                    data.forEach(student => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td class="border border-gray-400 px-4 py-2">${student.id}</td>
                            <td class="border border-gray-400 px-4 py-2">${student.name}</td>
                            <td class="border border-gray-400 px-4 py-2">
                                <a href="edit_students.php?id=${student.id}" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2 px-4 rounded">Edit</a>
                                <button class="bg-teal-500 hover:bg-teal-700 text-white font-bold py-2 px-4 rounded" onclick="deleteStudent(${student.id})">Delete</button>
                            </td>
                        `;
                        studentsList.appendChild(row);
                    });
                });
        }

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
$conn = new PDO('sqlite:students.db');

// Search query
if (isset($_GET['search'])) {
    $searchQuery = $_GET['search'];
    $stmt = $conn->prepare('SELECT * FROM students WHERE name LIKE :search');
    $stmt->bindParam(':search', $searchQuery);
    $stmt->execute();
    $students = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    $stmt = $conn->prepare('SELECT * FROM students');
    $stmt->execute();
    $students = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Delete query
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $conn->prepare('DELETE FROM students WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    echo json_encode(['success' => true]);
    exit;
}

// Return students list
echo json_encode($students);
?>