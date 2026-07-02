**create_مؤسسات.php**

<?php
// Session validation
if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit;
}

// Include header and navigation
require_once '../includes/header.php';
require_once '../includes/navigation.php';

// Include form script
require_once '../includes/form_script.php';

?>

<div class="container mx-auto p-4 pt-6 md:p-6 lg:px-12 xl:px-24">
    <div class="bg-white rounded-lg shadow-md p-4 md:p-6 lg:p-8 xl:p-12">
        <h2 class="text-lg font-bold text-emerald-600 mb-4">إضافة مؤسسة جديدة</h2>
        <form id="create_institutes_form" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">اسم المؤسسة</label>
                    <input type="text" id="name" name="name" class="block w-full px-4 py-2 mt-2 text-gray-700 bg-white border border-gray-300 rounded-lg focus:outline-none focus:ring focus:border-emerald-600" required>
                </div>
                <div>
                    <label for="address" class="block text-sm font-medium text-gray-700">العنوان</label>
                    <input type="text" id="address" name="address" class="block w-full px-4 py-2 mt-2 text-gray-700 bg-white border border-gray-300 rounded-lg focus:outline-none focus:ring focus:border-emerald-600" required>
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700">رقم الهاتف</label>
                    <input type="tel" id="phone" name="phone" class="block w-full px-4 py-2 mt-2 text-gray-700 bg-white border border-gray-300 rounded-lg focus:outline-none focus:ring focus:border-emerald-600" required>
                </div>
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">البريد الإلكتروني</label>
                    <input type="email" id="email" name="email" class="block w-full px-4 py-2 mt-2 text-gray-700 bg-white border border-gray-300 rounded-lg focus:outline-none focus:ring focus:border-emerald-600" required>
                </div>
            </div>
            <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2 px-4 rounded-lg">حفظ</button>
        </form>
    </div>
</div>

<?php
// Include footer
require_once '../includes/footer.php';
?>

<script>
    $(document).ready(function() {
        $('#create_institutes_form').submit(function(e) {
            e.preventDefault();
            var formData = $(this).serialize();
            $.ajax({
                type: 'POST',
                url: '../backend/مؤسسات.php',
                data: formData,
                success: function(response) {
                    if (response === 'success') {
                        window.location.href = '../list_مؤسسات.php';
                    } else {
                        alert('Error: ' + response);
                    }
                }
            });
        });
    });
</script>


**../backend/مؤسسات.php**

<?php
// Check if form data is submitted
if (isset($_POST['name']) && isset($_POST['address']) && isset($_POST['phone']) && isset($_POST['email'])) {
    // Connect to database
    $conn = mysqli_connect('localhost', 'username', 'password', 'database');
    if (!$conn) {
        die('Error: ' . mysqli_connect_error());
    }

    // Prepare and execute query
    $query = "INSERT INTO مؤسسات (name, address, phone, email) VALUES (?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, 'ssss', $_POST['name'], $_POST['address'], $_POST['phone'], $_POST['email']);
    mysqli_stmt_execute($stmt);

    // Check if query was successful
    if (mysqli_stmt_affected_rows($stmt) === 1) {
        echo 'success';
    } else {
        echo 'Error: ' . mysqli_error($conn);
    }

    // Close database connection
    mysqli_close($conn);
} else {
    echo 'Error: Form data not submitted';
}
?>