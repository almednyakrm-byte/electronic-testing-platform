**list_courses.php**

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
    <title>Courses</title>
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
            <a href="index.php" class="text-lg font-bold">Home</a>
            <div class="flex items-center">
                <p class="mr-4">Welcome, <?= $_SESSION['username'] ?></p>
                <a href="logout.php" class="text-red-600 hover:text-red-800">Logout</a>
            </div>
        </nav>
    </header>
    <main class="container mx-auto p-4 mt-4">
        <h1 class="text-3xl font-bold mb-4">Courses</h1>
        <div class="flex justify-between items-center mb-4">
            <button class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='create_courses.php'">Add New Item</button>
            <input type="search" id="search" class="w-full p-2 pl-10 text-sm text-gray-700 bg-gray-100 rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-200" placeholder="Search...">
        </div>
        <table class="w-full table-auto">
            <thead class="bg-gray-200">
                <tr>
                    <th class="px-4 py-2">ID</th>
                    <th class="px-4 py-2">Name</th>
                    <th class="px-4 py-2">Actions</th>
                </tr>
            </thead>
            <tbody id="courses-list">
                <?php
                // Fetch courses list from backend
                $response = file_get_contents('../backend/courses.php');
                $courses = json_decode($response, true);
                foreach ($courses as $course) {
                    ?>
                    <tr>
                        <td class="px-4 py-2"><?= $course['id'] ?></td>
                        <td class="px-4 py-2"><?= $course['name'] ?></td>
                        <td class="px-4 py-2">
                            <a href="edit_courses.php?id=<?= $course['id'] ?>" class="text-teal-500 hover:text-teal-700">Edit</a>
                            <button class="ml-4 text-red-600 hover:text-red-800" onclick="deleteCourse(<?= $course['id'] ?>)">Delete</button>
                        </td>
                    </tr>
                    <?php
                }
                ?>
            </tbody>
        </table>
    </main>

    <script>
        // Search bar filtering
        const searchInput = document.getElementById('search');
        const coursesList = document.getElementById('courses-list');

        searchInput.addEventListener('input', () => {
            const searchValue = searchInput.value.toLowerCase();
            const courses = coursesList.children;
            for (let i = 0; i < courses.length; i++) {
                const course = courses[i];
                const name = course.children[1].textContent.toLowerCase();
                if (name.includes(searchValue)) {
                    course.style.display = 'table-row';
                } else {
                    course.style.display = 'none';
                }
            }
        });

        // Delete course using AJAX
        function deleteCourse(id) {
            fetch('../backend/delete_course.php', {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ id: id })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Course deleted successfully!');
                    window.location.reload();
                } else {
                    alert('Error deleting course!');
                }
            })
            .catch(error => console.error('Error:', error));
        }
    </script>
</body>
</html>

Note: This code assumes that you have a `courses.php` file in the `../backend` directory that returns a JSON array of courses. You'll need to modify the `delete_course.php` file to handle the DELETE request and return a JSON response.