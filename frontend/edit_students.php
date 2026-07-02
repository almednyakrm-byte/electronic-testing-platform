**edit_students.php**

<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

// Get student ID from URL
$id = $_GET['id'];

// Fetch existing record details via GET
$existingRecord = json_decode(file_get_contents('../backend/students.php?id=' . $id), true);

// Check if record exists
if (empty($existingRecord)) {
    echo 'Record not found';
    exit;
}

// Set page title and mod slug
$pageTitle = 'Edit Student';
$modSlug = 'students';

// Include header and navigation
include 'header.php';
include 'navigation.php';
?>

<div class="container mx-auto p-4 pt-6 md:p-6 lg:p-12 xl:p-12">
    <h1 class="text-3xl font-bold mb-4"><?= $pageTitle ?></h1>
    <form id="edit-student-form" class="bg-white rounded shadow-md p-4">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700">Name:</label>
                <input type="text" id="name" name="name" class="block w-full px-4 py-2 text-sm text-gray-700 border border-gray-300 rounded-md focus:ring-emerald-600 focus:border-emerald-600" value="<?= $existingRecord['name'] ?>">
            </div>
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700">Email:</label>
                <input type="email" id="email" name="email" class="block w-full px-4 py-2 text-sm text-gray-700 border border-gray-300 rounded-md focus:ring-emerald-600 focus:border-emerald-600" value="<?= $existingRecord['email'] ?>">
            </div>
        </div>
        <div class="mt-4">
            <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2 px-4 rounded">Update Student</button>
        </div>
    </form>
</div>

<script>
    // Fetch existing record details via GET
    fetch('../backend/students.php?id=' + <?= $id ?>)
        .then(response => response.json())
        .then(data => {
            // Populate form fields
            document.getElementById('name').value = data.name;
            document.getElementById('email').value = data.email;
        })
        .catch(error => console.error(error));

    // Handle form submission
    document.getElementById('edit-student-form').addEventListener('submit', event => {
        event.preventDefault();

        // Send AJAX PUT request
        fetch('../backend/students.php', {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                id: <?= $id ?>,
                name: document.getElementById('name').value,
                email: document.getElementById('email').value
            })
        })
        .then(response => response.json())
        .then(data => {
            // Redirect to list page
            window.location.href = 'list_' + <?= $modSlug ?> + '.php';
        })
        .catch(error => console.error(error));
    });
</script>

<?php
// Include footer
include 'footer.php';
?>


**students.php (backend)**

<?php
// Check if ID is set
if (isset($_GET['id'])) {
    // Get student data from database
    $id = $_GET['id'];
    $student = getStudent($id);

    // Output student data as JSON
    echo json_encode($student);
} elseif (isset($_POST['id'])) {
    // Update student data in database
    updateStudent($_POST['id'], $_POST['name'], $_POST['email']);

    // Output success message
    echo 'Student updated successfully';
} else {
    // Output error message
    echo 'Invalid request';
}

// Function to get student data from database
function getStudent($id) {
    // Connect to database
    $conn = new PDO('mysql:host=localhost;dbname=database', 'username', 'password');

    // Query database
    $stmt = $conn->prepare('SELECT * FROM students WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->execute();

    // Fetch student data
    $student = $stmt->fetch();

    // Close database connection
    $conn = null;

    return $student;
}

// Function to update student data in database
function updateStudent($id, $name, $email) {
    // Connect to database
    $conn = new PDO('mysql:host=localhost;dbname=database', 'username', 'password');

    // Query database
    $stmt = $conn->prepare('UPDATE students SET name = :name, email = :email WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':email', $email);
    $stmt->execute();

    // Close database connection
    $conn = null;
}
?>