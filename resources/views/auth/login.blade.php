<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - FAB Retail</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        .spinner {
            animation: spin 1s linear infinite;
        }
        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
    </style>
</head>
<body class="bg-gray-100">
    <div class="min-h-screen flex items-center justify-center px-4">
        <div class="bg-white p-8 rounded-lg shadow-md w-full max-w-md">
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-blue-600">FAB Retail</h1>
                <p class="text-gray-600 mt-2">Management System</p>
            </div>

            <form id="loginForm" class="space-y-6">
                @csrf
                <div>
                    <label for="username" class="block text-sm font-medium text-gray-700 mb-1">Username</label>
                    <input type="text" id="username" name="username" required
                           class="w-full px-4 py-2 border rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                           placeholder="Masukkan username">
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                    <input type="password" id="password" name="password" required
                           class="w-full px-4 py-2 border rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                           placeholder="Masukkan password">
                </div>

                <div>
                    <button type="submit" id="loginButton"
                            class="w-full bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition flex justify-center items-center">
                        <span id="buttonText">Login</span>
                        <svg id="spinner" class="hidden w-5 h-5 ml-2 text-white spinner" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </button>
                </div>
            </form>

            <div id="errorMessage" class="mt-4 p-3 text-red-700 bg-red-100 rounded-md hidden"></div>

            @if(session('error'))
                <div class="mt-4 p-3 text-red-700 bg-red-100 rounded-md">
                    {{ session('error') }}
                </div>
            @endif
        </div>
    </div>

    <script>
        document.getElementById('loginForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const button = document.getElementById('loginButton');
            button.disabled = true;
            button.innerHTML = `Loading...`;

            try {
                const response = await fetch('http://fabretail.test:8080/api/auth/login', {
                    method: 'POST',
                    credentials: 'include', // Penting agar cookies dikirim
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({
                        username: document.getElementById('username').value,
                        password: document.getElementById('password').value
                    })
                });

                if (!response.ok) {
                    const errorData = await response.json();
                    throw new Error(errorData.error || 'Login failed');
                }

                console.log('Cookies setelah login:', document.cookie); // Tambahkan ini

                setTimeout(() => {
                    window.location.href = '/beranda';
                }, 500);

            } catch (error) {
                console.error('Error:', error);
                document.getElementById('errorMessage').innerText = error.message;
                document.getElementById('errorMessage').classList.remove('hidden');
            } finally {
                button.disabled = false;
                button.innerHTML = `Login`;
            }
        });
    </script>
</body>
</html>