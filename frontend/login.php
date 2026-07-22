<!-- login.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.tailwindcss.com" rel="stylesheet">
    <style>
        body {
            background-image: linear-gradient(to bottom, #f0f0f0, #f0f0f0);
        }
        .glassmorphic {
            background: linear-gradient(90deg, #f0f0f0 0%, #f0f0f0 100%);
            background-clip: padding-box;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .gradient {
            background: linear-gradient(90deg, #f0f0f0 0%, #f0f0f0 100%);
            background-clip: padding-box;
            padding: 10px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body class="bg-gray-100">
    <div class="max-w-md mx-auto p-4 mt-12 glassmorphic">
        <h2 class="text-3xl font-bold text-emerald-600 mb-4">Login</h2>
        <form id="login-form">
            <div class="mb-4">
                <label for="username" class="block text-gray-700 text-sm font-bold mb-2">Username</label>
                <input type="text" id="username" name="username" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" pattern="[A-Za-z\u0600-\u06FF0-9\s]+" required>
                <p id="username-error" class="text-red-500 hidden"></p>
            </div>
            <div class="mb-4">
                <label for="password" class="block text-gray-700 text-sm font-bold mb-2">Password</label>
                <input type="password" id="password" name="password" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                <p id="password-error" class="text-red-500 hidden"></p>
            </div>
            <button type="submit" class="bg-teal-500 hover:bg-teal-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Login</button>
            <p class="text-gray-700 text-sm mt-2">Don't have an account? <a href="register.php" class="text-emerald-600 hover:text-emerald-800">Register</a></p>
        </form>
    </div>

    <script>
        const form = document.getElementById('login-form');
        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            const username = document.getElementById('username').value;
            const password = document.getElementById('password').value;
            try {
                const response = await fetch('../backend/auth.php?action=login', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ username, password })
                });
                const data = await response.json();
                if (data.success) {
                    alert('Login successful!');
                    window.location.href = 'dashboard.php';
                } else {
                    alert(data.message);
                }
            } catch (error) {
                alert('Error: ' + error.message);
            }
        });

        document.getElementById('username').addEventListener('input', () => {
            const username = document.getElementById('username').value;
            if (username.length < 3) {
                document.getElementById('username-error').classList.remove('hidden');
                document.getElementById('username-error').textContent = 'Username must be at least 3 characters long.';
            } else {
                document.getElementById('username-error').classList.add('hidden');
                document.getElementById('username-error').textContent = '';
            }
        });

        document.getElementById('password').addEventListener('input', () => {
            const password = document.getElementById('password').value;
            if (password.length < 6) {
                document.getElementById('password-error').classList.remove('hidden');
                document.getElementById('password-error').textContent = 'Password must be at least 6 characters long.';
            } else {
                document.getElementById('password-error').classList.add('hidden');
                document.getElementById('password-error').textContent = '';
            }
        });
    </script>
</body>
</html>


This code creates a premium-looking login page with a glassmorphic layout, gradients, and a form for username and password input. It uses Tailwind CSS CDN for styling and includes standard HTML input pattern validators for Arabic and Latin characters. The form is submitted using AJAX with the Fetch API, and the response or error is handled dynamically using JavaScript. The code also includes a link to the register page and handles form validation for username and password.