**edit_students.php**

<?php
// Session validation
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Get student ID from URL
$id = $_GET['id'];

// Fetch existing record details via GET
$student = json_decode(file_get_contents('../backend/students.php?id=' . $id), true);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Student</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mx-auto p-4 mt-10">
        <h1 class="text-3xl font-bold text-emerald-600 mb-4">Edit Student</h1>
        <form id="edit-student-form">
            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                <input type="text" id="name" name="name" class="block w-full px-4 py-2 text-sm text-gray-700 border border-gray-300 rounded-md focus:ring-emerald-600 focus:border-emerald-600" value="<?= $student['name'] ?>">
            </div>
            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                <input type="email" id="email" name="email" class="block w-full px-4 py-2 text-sm text-gray-700 border border-gray-300 rounded-md focus:ring-emerald-600 focus:border-emerald-600" value="<?= $student['email'] ?>">
            </div>
            <div class="mb-4">
                <label for="phone" class="block text-sm font-medium text-gray-700">Phone</label>
                <input type="tel" id="phone" name="phone" class="block w-full px-4 py-2 text-sm text-gray-700 border border-gray-300 rounded-md focus:ring-emerald-600 focus:border-emerald-600" value="<?= $student['phone'] ?>">
            </div>
            <button type="submit" class="bg-teal-500 hover:bg-teal-700 text-white font-bold py-2 px-4 rounded">Update Student</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#edit-student-form').submit(function(e) {
                e.preventDefault();
                var formData = $(this).serialize();
                $.ajax({
                    type: 'PUT',
                    url: '../backend/students.php',
                    data: formData,
                    success: function(response) {
                        if (response.status === 'success') {
                            window.location.href = 'list_students.php';
                        } else {
                            alert(response.message);
                        }
                    }
                });
            });
        });
    </script>
</body>
</html>


**students.php (backend)**

<?php
// Check if student ID is set
if (!isset($_GET['id'])) {
    http_response_code(400);
    echo json_encode(array('status' => 'error', 'message' => 'Student ID is required'));
    exit;
}

// Get student ID
$id = $_GET['id'];

// Fetch existing record details
$student = get_student($id);

if ($student) {
    echo json_encode($student);
} else {
    http_response_code(404);
    echo json_encode(array('status' => 'error', 'message' => 'Student not found'));
}

function get_student($id) {
    // Database query to fetch student details
    // Replace with your actual database query
    $student = array(
        'id' => $id,
        'name' => 'John Doe',
        'email' => 'john.doe@example.com',
        'phone' => '1234567890'
    );
    return $student;
}
?>