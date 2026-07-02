<?php
// Start session
session_start();

// Session validation
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

// Include database connection
require_once '../backend/db.php';

// Module slug
$mod_slug = 'teachers';

// Page title
$page_title = 'Create Teacher';

// Include header
require_once 'header.php';
?>

<main class="md:flex-row flex flex-col items-center justify-center h-screen">
    <div class="md:w-1/2 w-full md:mx-0 mx-4 md:my-0 my-4 md:p-0 p-4 md:rounded-none rounded-md md:shadow-none shadow-md md:bg-transparent bg-white">
        <h2 class="text-emerald-600 text-2xl font-bold mb-4">Create Teacher</h2>
        <form id="create-teacher-form">
            <div class="mb-4">
                <label for="first_name" class="block text-emerald-600 text-sm font-medium mb-2">First Name:</label>
                <input type="text" id="first_name" name="first_name" class="block p-2 w-full text-sm text-gray-900 bg-gray-50 rounded-md shadow-sm border border-gray-300 focus:ring-emerald-600 focus:border-emerald-600">
            </div>
            <div class="mb-4">
                <label for="last_name" class="block text-emerald-600 text-sm font-medium mb-2">Last Name:</label>
                <input type="text" id="last_name" name="last_name" class="block p-2 w-full text-sm text-gray-900 bg-gray-50 rounded-md shadow-sm border border-gray-300 focus:ring-emerald-600 focus:border-emerald-600">
            </div>
            <div class="mb-4">
                <label for="email" class="block text-emerald-600 text-sm font-medium mb-2">Email:</label>
                <input type="email" id="email" name="email" class="block p-2 w-full text-sm text-gray-900 bg-gray-50 rounded-md shadow-sm border border-gray-300 focus:ring-emerald-600 focus:border-emerald-600">
            </div>
            <div class="mb-4">
                <label for="phone" class="block text-emerald-600 text-sm font-medium mb-2">Phone:</label>
                <input type="text" id="phone" name="phone" class="block p-2 w-full text-sm text-gray-900 bg-gray-50 rounded-md shadow-sm border border-gray-300 focus:ring-emerald-600 focus:border-emerald-600">
            </div>
            <div class="mb-4">
                <label for="subject" class="block text-emerald-600 text-sm font-medium mb-2">Subject:</label>
                <select id="subject" name="subject" class="block p-2 w-full text-sm text-gray-900 bg-gray-50 rounded-md shadow-sm border border-gray-300 focus:ring-emerald-600 focus:border-emerald-600">
                    <option value="">Select Subject</option>
                    <option value="Math">Math</option>
                    <option value="Science">Science</option>
                    <option value="English">English</option>
                </select>
            </div>
            <button type="submit" class="text-white bg-emerald-600 hover:bg-emerald-700 focus:ring-4 focus:ring-emerald-200 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-emerald-600 dark:hover:bg-emerald-700 dark:focus:ring-emerald-800">Create Teacher</button>
        </form>
    </div>
</main>

<script>
    $(document).ready(function() {
        $('#create-teacher-form').submit(function(e) {
            e.preventDefault();
            $.ajax({
                type: 'POST',
                url: '../backend/teachers.php',
                data: $(this).serialize(),
                success: function(data) {
                    window.location.href = 'list_<?php echo $mod_slug; ?>.php';
                }
            });
        });
    });
</script>