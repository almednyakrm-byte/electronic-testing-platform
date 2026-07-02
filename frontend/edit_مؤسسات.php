<?php
// edit_مؤسسات.php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

if (!isset($_GET['id'])) {
    header('Location: list_مؤسسات.php');
    exit;
}

$id = $_GET['id'];
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تعديل مؤسسة</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mx-auto p-4 pt-6 mt-10 bg-white rounded-lg shadow-md">
        <h2 class="text-2xl text-emerald-600 mb-4">تعديل مؤسسة</h2>
        <form id="edit-form">
            <div class="mb-4">
                <label for="name" class="block text-sm text-gray-600 mb-2">اسم المؤسسة</label>
                <input type="text" id="name" name="name" class="block w-full p-2 pl-10 text-sm text-gray-700 border border-gray-200 rounded-lg focus:outline-none focus:ring-emerald-600 focus:border-emerald-600">
            </div>
            <div class="mb-4">
                <label for="description" class="block text-sm text-gray-600 mb-2">وصف المؤسسة</label>
                <textarea id="description" name="description" class="block w-full p-2 pl-10 text-sm text-gray-700 border border-gray-200 rounded-lg focus:outline-none focus:ring-emerald-600 focus:border-emerald-600"></textarea>
            </div>
            <button type="submit" class="bg-emerald-600 hover:bg-teal-500 text-white font-bold py-2 px-4 rounded-lg">حفظ التعديلات</button>
        </form>
    </div>

    <script>
        const id = '<?php echo $id; ?>';
        const form = document.getElementById('edit-form');

        // Fetch existing record details
        fetch(`../backend/مؤسسات.php?id=${id}`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('name').value = data.name;
                document.getElementById('description').value = data.description;
            });

        // Submit form using AJAX
        form.addEventListener('submit', (e) => {
            e.preventDefault();
            const formData = new FormData(form);
            fetch('../backend/مؤسسات.php', {
                method: 'PUT',
                body: formData,
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.href = 'list_مؤسسات.php';
                } else {
                    console.error(data.error);
                }
            })
            .catch(error => console.error(error));
        });
    </script>
</body>
</html>