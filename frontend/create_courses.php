**create_courses.php**

<?php
// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

// Include header
include 'header.php';

// Include Tailwind CSS
?>
<link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">

<?php
// Include premium Tailwind UI form
?>
<div class="container mx-auto p-4 mt-10">
    <h2 class="text-3xl font-bold text-emerald-600 mb-4">Create New Course</h2>
    <form id="create-course-form" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="course_name">Course Name</label>
            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="course_name" type="text" placeholder="Enter course name">
        </div>
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="course_description">Course Description</label>
            <textarea class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="course_description" placeholder="Enter course description"></textarea>
        </div>
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="course_price">Course Price</label>
            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="course_price" type="number" placeholder="Enter course price">
        </div>
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="course_duration">Course Duration</label>
            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="course_duration" type="text" placeholder="Enter course duration">
        </div>
        <button type="submit" class="bg-teal-500 hover:bg-teal-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Create Course</button>
    </form>
</div>

<?php
// Include AJAX script
?>
<script>
    $(document).ready(function() {
        $('#create-course-form').submit(function(e) {
            e.preventDefault();
            var formData = $(this).serialize();
            $.ajax({
                type: 'POST',
                url: '../backend/courses.php',
                data: formData,
                success: function(response) {
                    if (response == 'success') {
                        window.location.href = 'list_courses.php';
                    } else {
                        alert('Error creating course');
                    }
                }
            });
        });
    });
</script>

<?php
// Include footer
include 'footer.php';
?>


**courses.php (backend)**

<?php
// Connect to database
$conn = mysqli_connect('localhost', 'username', 'password', 'database');

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Get form data
$course_name = $_POST['course_name'];
$course_description = $_POST['course_description'];
$course_price = $_POST['course_price'];
$course_duration = $_POST['course_duration'];

// Insert data into database
$sql = "INSERT INTO courses (course_name, course_description, course_price, course_duration) VALUES ('$course_name', '$course_description', '$course_price', '$course_duration')";
if (mysqli_query($conn, $sql)) {
    echo 'success';
} else {
    echo 'Error creating course';
}

// Close connection
mysqli_close($conn);
?>