**create_مادة-اختبار.php**

<?php
// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit;
}

// Include database connection
require_once '../config/database.php';

// Check if form has been submitted
if (isset($_POST['submit'])) {
    // Validate form data
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $category = trim($_POST['category']);

    // Check if all fields are filled
    if (!empty($name) && !empty($description) && !empty($category)) {
        // Insert data into database
        $sql = "INSERT INTO مادة_اختبار (name, description, category) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $name, $description, $category);
        $stmt->execute();

        // Redirect back to list page
        header('Location: list_مادة-اختبار.php');
        exit;
    } else {
        $error = 'Please fill all fields';
    }
}

// Include header
require_once '../includes/header.php';

// Include Tailwind CSS
?>
<link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">

<!-- Create مادة_اختبار form -->
<div class="max-w-md mx-auto p-4 bg-white rounded-lg shadow-md">
    <h2 class="text-lg font-bold text-emerald-600 mb-4">Create مادة_اختبار</h2>
    <form id="create-form" method="POST">
        <div class="mb-4">
            <label for="name" class="block text-sm font-bold text-gray-700">Name:</label>
            <input type="text" id="name" name="name" class="block w-full p-2 mt-1 text-sm text-gray-700 border-gray-300 rounded-lg focus:ring-teal-500 focus:border-teal-500" required>
        </div>
        <div class="mb-4">
            <label for="description" class="block text-sm font-bold text-gray-700">Description:</label>
            <textarea id="description" name="description" class="block w-full p-2 mt-1 text-sm text-gray-700 border-gray-300 rounded-lg focus:ring-teal-500 focus:border-teal-500" required></textarea>
        </div>
        <div class="mb-4">
            <label for="category" class="block text-sm font-bold text-gray-700">Category:</label>
            <select id="category" name="category" class="block w-full p-2 mt-1 text-sm text-gray-700 border-gray-300 rounded-lg focus:ring-teal-500 focus:border-teal-500" required>
                <option value="">Select Category</option>
                <!-- Add options here -->
            </select>
        </div>
        <button type="submit" name="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2 px-4 rounded-lg">Create</button>
        <?php if (isset($error)) : ?>
            <p class="text-red-500 mt-2"><?= $error ?></p>
        <?php endif; ?>
    </form>
</div>

<!-- Include JavaScript -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $('#create-form').submit(function(e) {
            e.preventDefault();
            $.ajax({
                type: 'POST',
                url: '../backend/مادة-اختبار.php',
                data: $(this).serialize(),
                success: function(response) {
                    if (response === 'success') {
                        window.location.href = 'list_مادة-اختبار.php';
                    } else {
                        alert('Error creating مادة_اختبار');
                    }
                }
            });
        });
    });
</script>

<!-- Include footer -->
<?php require_once '../includes/footer.php'; ?>


**Note:** Replace `../backend/مادة-اختبار.php` with the actual PHP file that handles the form submission and database insertion.

**Note:** Add options to the `category` select element as needed.

**Note:** This code assumes that the `logged_in` session variable is set to `true` when the user is logged in. You may need to modify this to fit your specific authentication system.