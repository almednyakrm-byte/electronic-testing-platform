**edit_مادة-تجريبية.php**

<?php
// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit;
}

// Get ID from URL
$id = $_GET['id'];

// Validate ID
if (empty($id)) {
    header('Location: list_مادة-تجريبية.php');
    exit;
}

// Fetch existing record details via AJAX
$js = "
    $(document).ready(function() {
        $.ajax({
            type: 'GET',
            url: '../backend/مادة-تجريبية.php?id=" . $id . "',
            dataType: 'json',
            success: function(data) {
                $('#material_name').val(data.material_name);
                $('#description').val(data.description);
                $('#price').val(data.price);
            }
        });
    });
";

// Include JavaScript code
echo "<script>$js</script>";

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit مادة تجريبية</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="max-w-md mx-auto p-4 bg-white rounded-md shadow-md">
        <h1 class="text-lg font-bold text-emerald-600 mb-4">Edit مادة تجريبية</h1>
        <form id="edit-material-form" class="space-y-4">
            <div class="flex flex-wrap -mx-3 mb-6">
                <div class="w-full px-3">
                    <label for="material_name" class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2">مادة</label>
                    <input type="text" id="material_name" name="material_name" class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" required>
                </div>
            </div>
            <div class="flex flex-wrap -mx-3 mb-6">
                <div class="w-full px-3">
                    <label for="description" class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2">وصف</label>
                    <textarea id="description" name="description" class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" required></textarea>
                </div>
            </div>
            <div class="flex flex-wrap -mx-3 mb-6">
                <div class="w-full px-3">
                    <label for="price" class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2">سعر</label>
                    <input type="number" id="price" name="price" class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" required>
                </div>
            </div>
            <button type="submit" class="bg-teal-500 hover:bg-teal-700 text-white font-bold py-2 px-4 rounded">حفظ</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#edit-material-form').submit(function(e) {
                e.preventDefault();
                $.ajax({
                    type: 'PUT',
                    url: '../backend/مادة-تجريبية.php',
                    data: $(this).serialize(),
                    dataType: 'json',
                    success: function(data) {
                        if (data.status === 'success') {
                            window.location.href = 'list_مادة-تجريبية.php';
                        } else {
                            alert('Error: ' + data.message);
                        }
                    }
                });
            });
        });
    </script>
</body>
</html>


**مادة-تجريبية.php (backend)**

<?php
// Check if ID is set
if (isset($_GET['id'])) {
    // Get ID
    $id = $_GET['id'];

    // Validate ID
    if (empty($id)) {
        header('Location: list_مادة-تجريبية.php');
        exit;
    }

    // Fetch existing record details
    $sql = "SELECT * FROM مادة_تجريبية WHERE id = '$id'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);

    // Output JSON
    echo json_encode($row);
} elseif (isset($_POST['material_name']) && isset($_POST['description']) && isset($_POST['price'])) {
    // Get data
    $material_name = $_POST['material_name'];
    $description = $_POST['description'];
    $price = $_POST['price'];

    // Update record
    $sql = "UPDATE مادة_تجريبية SET material_name = '$material_name', description = '$description', price = '$price' WHERE id = '$id'";
    $result = mysqli_query($conn, $sql);

    // Output JSON
    if ($result) {
        echo json_encode(array('status' => 'success'));
    } else {
        echo json_encode(array('status' => 'error', 'message' => 'Error updating record'));
    }
}
?>