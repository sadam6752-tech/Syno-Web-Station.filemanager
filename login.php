<?php
require_once 'config.php';
require_once 'auth.php';

// Если авторизация отключена, перенаправляем на главную
if (!AUTH_ENABLED) {
    header('Location: index.php');
    exit;
}

// Если уже авторизован, перенаправляем на главную
if (checkAuth()) {
    header('Location: index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - File Manager</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
        }

        .login-container {
            background: white;
            border-radius: 12px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
            padding: 2.5rem;
            width: 100%;
            max-width: 400px;
        }

        .login-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .login-header i {
            font-size: 3rem;
            color: #667eea;
            margin-bottom: 1rem;
        }

        .login-header h1 {
            font-size: 1.75rem;
            color: #333;
            margin-bottom: 0.5rem;
        }

        .login-header p {
            color: #666;
            font-size: 0.9rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            color: #333;
            font-weight: 500;
        }

        .input-wrapper {
            position: relative;
        }

        .input-wrapper i {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #999;
        }

        .form-group input {
            width: 100%;
            padding: 0.875rem 1rem 0.875rem 2.75rem;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 1rem;
            transition: border-color 0.3s;
        }

        .form-group input:focus {
            outline: none;
            border-color: #667eea;
        }

        .error-message {
            background: #fee;
            color: #c33;
            padding: 0.75rem;
            border-radius: 6px;
            margin-bottom: 1rem;
            display: none;
            font-size: 0.9rem;
        }

        .error-message.show {
            display: block;
        }

        .login-button {
            width: 100%;
            padding: 1rem;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .login-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }

        .login-button:active {
            transform: translateY(0);
        }

        .login-button:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
        }

        .login-footer {
            text-align: center;
            margin-top: 1.5rem;
            color: #666;
            font-size: 0.85rem;
        }

        @media (max-width: 480px) {
            .login-container {
                padding: 2rem 1.5rem;
            }

            .login-header h1 {
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            <i class="fas fa-folder-open"></i>
            <h1>File Manager</h1>
            <p>Please sign in to continue</p>
        </div>

        <div id="error-message" class="error-message"></div>

        <form id="login-form">
            <div class="form-group">
                <label for="username">Username</label>
                <div class="input-wrapper">
                    <i class="fas fa-user"></i>
                    <input type="text" id="username" name="username" required autofocus>
                </div>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <div class="input-wrapper">
                    <i class="fas fa-lock"></i>
                    <input type="password" id="password" name="password" required>
                </div>
            </div>

            <button type="submit" class="login-button" id="login-button">
                <i class="fas fa-sign-in-alt"></i> Sign In
            </button>
        </form>

        <div class="login-footer">
            Synology NAS File Manager
        </div>
    </div>

    <script>
        const form = document.getElementById('login-form');
        const errorMessage = document.getElementById('error-message');
        const loginButton = document.getElementById('login-button');

        form.addEventListener('submit', async (e) => {
            e.preventDefault();

            const username = document.getElementById('username').value;
            const password = document.getElementById('password').value;

            errorMessage.classList.remove('show');
            loginButton.disabled = true;
            loginButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Signing in...';

            try {
                const formData = new FormData();
                formData.append('action', 'login');
                formData.append('username', username);
                formData.append('password', password);

                const response = await fetch('auth.php', {
                    method: 'POST',
                    body: formData
                });

                const data = await response.json();

                if (data.success) {
                    window.location.href = 'index.php';
                } else {
                    throw new Error(data.error || 'Login failed');
                }
            } catch (error) {
                errorMessage.textContent = error.message || 'Invalid username or password';
                errorMessage.classList.add('show');
                loginButton.disabled = false;
                loginButton.innerHTML = '<i class="fas fa-sign-in-alt"></i> Sign In';
            }
        });
    </script>
</body>
</html>
