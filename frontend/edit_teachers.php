**edit_teachers.php**

<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

// Get the id from the URL
$id = $_GET['id'];

// Fetch the existing record details via GET
$teachers = json_decode(file_get_contents('../backend/teachers.php?id=' . $id), true);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Teacher</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="max-w-md mx-auto p-4 bg-white rounded-md shadow-md">
        <h2 class="text-lg font-bold text-emerald-600 mb-4">Edit Teacher</h2>
        <form id="edit-teacher-form">
            <div class="mb-4">
                <label for="name" class="block text-sm font-bold text-gray-700">Name:</label>
                <input type="text" id="name" name="name" class="w-full p-2 mb-2 text-sm text-gray-700 border border-gray-300 rounded-md" value="<?= $teachers['name'] ?>">
            </div>
            <div class="mb-4">
                <label for="email" class="block text-sm font-bold text-gray-700">Email:</label>
                <input type="email" id="email" name="email" class="w-full p-2 mb-2 text-sm text-gray-700 border border-gray-300 rounded-md" value="<?= $teachers['email'] ?>">
            </div>
            <div class="mb-4">
                <label for="phone" class="block text-sm font-bold text-gray-700">Phone:</label>
                <input type="tel" id="phone" name="phone" class="w-full p-2 mb-2 text-sm text-gray-700 border border-gray-300 rounded-md" value="<?= $teachers['phone'] ?>">
            </div>
            <button type="submit" class="bg-teal-500 hover:bg-teal-700 text-white font-bold py-2 px-4 rounded">Update Teacher</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#edit-teacher-form').submit(function(e) {
                e.preventDefault();
                var formData = $(this).serialize();
                $.ajax({
                    type: 'PUT',
                    url: '../backend/teachers.php',
                    data: formData,
                    success: function(response) {
                        if (response.status === 'success') {
                            window.location.href = 'list_teachers.php';
                        } else {
                            alert('Error updating teacher');
                        }
                    }
                });
            });
        });
    </script>
</body>
</html>


**teachers.php (backend)**

<?php
// Check if id is set
if (isset($_GET['id'])) {
    // Connect to database
    $conn = new mysqli('localhost', 'username', 'password', 'database');

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Get the id
    $id = $_GET['id'];

    // Query to get the teacher details
    $sql = "SELECT * FROM teachers WHERE id = '$id'";
    $result = $conn->query($sql);

    // Check if result is not empty
    if ($result->num_rows > 0) {
        // Fetch the teacher details
        $teacher = $result->fetch_assoc();
        echo json_encode($teacher);
    } else {
        echo json_encode(array('error' => 'Teacher not found'));
    }

    // Close the connection
    $conn->close();
} else {
    echo json_encode(array('error' => 'Invalid id'));
}
?>


**teachers.php (backend) - PUT request**

<?php
// Check if id is set
if (isset($_GET['id'])) {
    // Connect to database
    $conn = new mysqli('localhost', 'username', 'password', 'database');

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Get the id
    $id = $_GET['id'];

    // Get the data from the request
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];

    // Query to update the teacher details
    $sql = "UPDATE teachers SET name = '$name', email = '$email', phone = '$phone' WHERE id = '$id'";
    $result = $conn->query($sql);

    // Check if result is not empty
    if ($result) {
        echo json_encode(array('status' => 'success'));
    } else {
        echo json_encode(array('status' => 'error'));
    }

    // Close the connection
    $conn->close();
} else {
    echo json_encode(array('error' => 'Invalid id'));
}
?>