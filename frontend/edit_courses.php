**edit_courses.php**

<?php
// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Get course ID from URL
$id = $_GET['id'];

// Fetch course details via AJAX
$course = json_decode(file_get_contents('../backend/courses.php?id=' . $id), true);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Course</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="max-w-md mx-auto p-4 bg-white rounded-md shadow-md">
        <h2 class="text-lg font-bold text-emerald-600 mb-4">Edit Course</h2>
        <form id="edit-course-form">
            <div class="mb-4">
                <label for="title" class="block text-sm font-bold text-gray-700 mb-2">Title</label>
                <input type="text" id="title" name="title" class="block w-full p-2 border border-gray-300 rounded-md" value="<?= $course['title'] ?>">
            </div>
            <div class="mb-4">
                <label for="description" class="block text-sm font-bold text-gray-700 mb-2">Description</label>
                <textarea id="description" name="description" class="block w-full p-2 border border-gray-300 rounded-md"><?= $course['description'] ?></textarea>
            </div>
            <div class="mb-4">
                <label for="price" class="block text-sm font-bold text-gray-700 mb-2">Price</label>
                <input type="number" id="price" name="price" class="block w-full p-2 border border-gray-300 rounded-md" value="<?= $course['price'] ?>">
            </div>
            <button type="submit" class="bg-teal-500 hover:bg-teal-700 text-white font-bold py-2 px-4 rounded">Update Course</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            // Fetch course details via AJAX
            $.ajax({
                type: 'GET',
                url: '../backend/courses.php?id=' + <?= $id ?>,
                success: function(data) {
                    var course = JSON.parse(data);
                    $('#title').val(course.title);
                    $('#description').val(course.description);
                    $('#price').val(course.price);
                }
            });

            // Submit form via AJAX
            $('#edit-course-form').submit(function(e) {
                e.preventDefault();
                var formData = $(this).serialize();
                $.ajax({
                    type: 'PUT',
                    url: '../backend/courses.php',
                    data: formData,
                    success: function() {
                        window.location.href = 'list_courses.php';
                    }
                });
            });
        });
    </script>
</body>
</html>


**courses.php (backend)**

<?php
// Connect to database
$conn = new mysqli('localhost', 'username', 'password', 'database');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get course ID from URL
$id = $_GET['id'];

// Fetch course details
$query = "SELECT * FROM courses WHERE id = '$id'";
$result = $conn->query($query);

// Check if course exists
if ($result->num_rows > 0) {
    // Fetch course details
    $course = $result->fetch_assoc();
    echo json_encode($course);
} else {
    echo json_encode(array('error' => 'Course not found'));
}

// Close connection
$conn->close();
?>


**courses.php (PUT request)**

<?php
// Connect to database
$conn = new mysqli('localhost', 'username', 'password', 'database');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get course ID and updated data from URL and request body
$id = $_GET['id'];
$data = json_decode(file_get_contents('php://input'), true);

// Update course details
$query = "UPDATE courses SET title = '$data[title]', description = '$data[description]', price = '$data[price]' WHERE id = '$id'";
$conn->query($query);

// Close connection
$conn->close();

// Redirect to list_courses.php
header('Location: list_courses.php');
exit;
?>