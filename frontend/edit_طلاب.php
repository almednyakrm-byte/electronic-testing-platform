**edit_طلاب.php**

<?php
// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

// Get id from URL
$id = $_GET['id'];

// Fetch existing record details via GET
$url = '../backend/طلاب.php?id=' . $id;
$response = file_get_contents($url);
$data = json_decode($response, true);

// Check if data exists
if (empty($data)) {
    echo 'Error: Record not found.';
    exit;
}

// Set page title and mod slug
$page_title = 'Edit طالب';
$mod_slug = 'طلاب';

// Include header and navigation
include 'header.php';
include 'navigation.php';
?>

<div class="container mx-auto p-4 pt-6 md:p-6 lg:p-12 xl:p-12">
    <div class="bg-white rounded-lg shadow-md p-4">
        <h2 class="text-lg font-bold text-emerald-600 mb-4"><?= $page_title ?></h2>
        <form id="edit-form" class="space-y-4">
            <div>
                <label for="name" class="block text-sm font-bold text-gray-700 mb-2">Name:</label>
                <input type="text" id="name" name="name" class="block w-full px-4 py-2 text-gray-700 bg-white border border-gray-300 rounded-lg focus:ring-emerald-600 focus:border-emerald-600" value="<?= $data['name'] ?>">
            </div>
            <div>
                <label for="email" class="block text-sm font-bold text-gray-700 mb-2">Email:</label>
                <input type="email" id="email" name="email" class="block w-full px-4 py-2 text-gray-700 bg-white border border-gray-300 rounded-lg focus:ring-emerald-600 focus:border-emerald-600" value="<?= $data['email'] ?>">
            </div>
            <div>
                <label for="phone" class="block text-sm font-bold text-gray-700 mb-2">Phone:</label>
                <input type="tel" id="phone" name="phone" class="block w-full px-4 py-2 text-gray-700 bg-white border border-gray-300 rounded-lg focus:ring-emerald-600 focus:border-emerald-600" value="<?= $data['phone'] ?>">
            </div>
            <button type="submit" class="bg-teal-500 hover:bg-teal-700 text-white font-bold py-2 px-4 rounded-lg">Update</button>
        </form>
    </div>
</div>

<script>
    // Fetch existing record details via GET
    fetch('../backend/طلاب.php?id=<?= $id ?>')
        .then(response => response.json())
        .then(data => {
            // Populate form fields
            document.getElementById('name').value = data.name;
            document.getElementById('email').value = data.email;
            document.getElementById('phone').value = data.phone;
        })
        .catch(error => console.error(error));

    // Submit form via AJAX PUT request
    document.getElementById('edit-form').addEventListener('submit', function(event) {
        event.preventDefault();
        const formData = new FormData(this);
        fetch('../backend/طلاب.php', {
            method: 'PUT',
            body: formData
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.href = 'list_<?= $mod_slug ?>.php';
                } else {
                    console.error(data.error);
                }
            })
            .catch(error => console.error(error));
    });
</script>

<?php
// Include footer
include 'footer.php';
?>


**backend/طلاب.php**

<?php
// Check if id exists
if (!isset($_GET['id'])) {
    echo json_encode(['error' => 'ID not found']);
    exit;
}

// Connect to database
$conn = mysqli_connect('localhost', 'username', 'password', 'database');

// Check connection
if (!$conn) {
    echo json_encode(['error' => 'Connection failed']);
    exit;
}

// Get id from URL
$id = $_GET['id'];

// Query to fetch existing record details
$query = "SELECT * FROM طالاب WHERE id = '$id'";
$result = mysqli_query($conn, $query);

// Check if record exists
if (mysqli_num_rows($result) > 0) {
    // Fetch record details
    $row = mysqli_fetch_assoc($result);
    echo json_encode($row);
} else {
    echo json_encode(['error' => 'Record not found']);
}

// Close connection
mysqli_close($conn);
?>


**backend/طلاب.php (PUT request handler)**

<?php
// Check if id exists
if (!isset($_GET['id'])) {
    echo json_encode(['error' => 'ID not found']);
    exit;
}

// Connect to database
$conn = mysqli_connect('localhost', 'username', 'password', 'database');

// Check connection
if (!$conn) {
    echo json_encode(['error' => 'Connection failed']);
    exit;
}

// Get id from URL
$id = $_GET['id'];

// Get form data
$name = $_POST['name'];
$email = $_POST['email'];
$phone = $_POST['phone'];

// Query to update existing record
$query = "UPDATE طالاب SET name = '$name', email = '$email', phone = '$phone' WHERE id = '$id'";
$result = mysqli_query($conn, $query);

// Check if update was successful
if ($result) {
    echo json_encode(['success' => 'Record updated successfully']);
} else {
    echo json_encode(['error' => 'Update failed']);
}

// Close connection
mysqli_close($conn);
?>