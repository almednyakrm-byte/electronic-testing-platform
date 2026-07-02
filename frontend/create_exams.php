**create_exams.php**

<?php
// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    header('Location: login.php');
    exit;
}

// Include header and navigation
require_once 'header.php';
require_once 'navigation.php';
?>

<div class="container mx-auto p-4 pt-6 md:p-6 lg:p-12 xl:p-12">
    <div class="bg-white rounded-lg shadow-md p-4 md:p-6 lg:p-8 xl:p-8">
        <h2 class="text-lg font-bold text-emerald-600 mb-4">Create Exam</h2>
        <form id="exam-form" class="space-y-4">
            <div class="flex flex-wrap -mx-3 mb-6">
                <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                    <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="exam_name">Exam Name</label>
                    <input class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="exam_name" type="text" placeholder="Exam Name">
                </div>
                <div class="w-full md:w-1/2 px-3">
                    <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="exam_date">Exam Date</label>
                    <input class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="exam_date" type="date" placeholder="Exam Date">
                </div>
            </div>
            <div class="flex flex-wrap -mx-3 mb-6">
                <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                    <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="exam_time">Exam Time</label>
                    <input class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="exam_time" type="time" placeholder="Exam Time">
                </div>
                <div class="w-full md:w-1/2 px-3">
                    <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="exam_location">Exam Location</label>
                    <input class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="exam_location" type="text" placeholder="Exam Location">
                </div>
            </div>
            <button type="submit" class="bg-teal-500 hover:bg-teal-700 text-white font-bold py-2 px-4 rounded">Create Exam</button>
        </form>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#exam-form').submit(function(event) {
            event.preventDefault();
            var formData = $(this).serialize();
            $.ajax({
                type: 'POST',
                url: '../backend/exams.php',
                data: formData,
                success: function(response) {
                    if (response === 'success') {
                        window.location.href = 'list_exams.php';
                    } else {
                        alert('Error creating exam');
                    }
                }
            });
        });
    });
</script>

<?php
// Include footer
require_once 'footer.php';
?>


**header.php**

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exams</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css">
</head>
<body>


**footer.php**

</body>
</html>


**navigation.php**

<nav class="bg-white shadow-md p-4 md:p-6 lg:p-8 xl:p-8">
    <ul class="flex justify-between items-center">
        <li class="mr-4"><a href="list_exams.php" class="text-gray-700 hover:text-gray-900">Exams</a></li>
        <li class="mr-4"><a href="create_exams.php" class="text-gray-700 hover:text-gray-900">Create Exam</a></li>
        <li class="mr-4"><a href="logout.php" class="text-gray-700 hover:text-gray-900">Logout</a></li>
    </ul>
</nav>


**backend/exams.php**

<?php
// Check if form data is submitted
if (isset($_POST['exam_name']) && isset($_POST['exam_date']) && isset($_POST['exam_time']) && isset($_POST['exam_location'])) {
    // Connect to database
    $conn = new mysqli('localhost', 'username', 'password', 'database');
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare and execute query
    $sql = "INSERT INTO exams (exam_name, exam_date, exam_time, exam_location) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $_POST['exam_name'], $_POST['exam_date'], $_POST['exam_time'], $_POST['exam_location']);
    $stmt->execute();
    $stmt->close();

    // Close connection
    $conn->close();

    // Return success response
    echo 'success';
} else {
    // Return error response
    echo 'Error creating exam';
}
?>