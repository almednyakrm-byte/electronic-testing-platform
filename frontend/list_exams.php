**list_exams.php**

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
    <title>Exams Management</title>
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
            <a href="index.php" class="text-lg font-bold">Home</a>
            <div class="flex items-center">
                <p class="mr-2">Welcome, <?php echo $_SESSION['username']; ?></p>
                <a href="logout.php" class="text-red-600 hover:text-red-800">Logout</a>
            </div>
        </nav>
    </header>
    <main class="max-w-7xl mx-auto p-4">
        <h1 class="text-3xl font-bold mb-4">Exams Management</h1>
        <div class="flex justify-between items-center mb-4">
            <button class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='create_exams.php'">Add New Item</button>
            <input type="search" id="search" class="w-full p-2 pl-10 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-600" placeholder="Search...">
        </div>
        <table class="w-full border-collapse border border-gray-400">
            <thead>
                <tr>
                    <th class="border border-gray-400 p-2">ID</th>
                    <th class="border border-gray-400 p-2">Name</th>
                    <th class="border border-gray-400 p-2">Actions</th>
                </tr>
            </thead>
            <tbody id="exam-list">
                <?php
                // Fetch data from backend
                $url = '../backend/exams.php';
                $response = fetch($url);
                $data = json_decode($response, true);
                foreach ($data as $exam) {
                    ?>
                    <tr>
                        <td class="border border-gray-400 p-2"><?php echo $exam['id']; ?></td>
                        <td class="border border-gray-400 p-2"><?php echo $exam['name']; ?></td>
                        <td class="border border-gray-400 p-2">
                            <a href="edit_exams.php?id=<?php echo $exam['id']; ?>" class="text-teal-500 hover:text-teal-700">Edit</a>
                            <button class="ml-2 text-red-600 hover:text-red-800" onclick="deleteExam(<?php echo $exam['id']; ?>)">Delete</button>
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
        searchInput.addEventListener('input', function() {
            const searchValue = this.value.toLowerCase();
            const examList = document.getElementById('exam-list');
            const examRows = examList.getElementsByTagName('tr');
            for (let i = 0; i < examRows.length; i++) {
                const examName = examRows[i].cells[1].textContent.toLowerCase();
                if (examName.includes(searchValue)) {
                    examRows[i].style.display = '';
                } else {
                    examRows[i].style.display = 'none';
                }
            }
        });

        // Delete exam via AJAX
        function deleteExam(id) {
            if (confirm('Are you sure you want to delete this exam?')) {
                fetch('../backend/exams.php', {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ id: id })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Exam deleted successfully!');
                        location.reload();
                    } else {
                        alert('Error deleting exam!');
                    }
                })
                .catch(error => console.error('Error:', error));
            }
        }

        // Fetch data from backend
        function fetch(url) {
            return fetch(url)
            .then(response => response.json())
            .catch(error => console.error('Error:', error));
        }
    </script>
</body>
</html>

**Note:** This code assumes that you have a backend PHP file `exams.php` that handles GET and DELETE requests for fetching and deleting exams. The `fetch` function in the JavaScript code is used to make AJAX requests to the backend.