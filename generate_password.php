<?php
/**
 * Утилита для генерации хешей паролей
 * 
 * Использование:
 * 
 * СПОСОБ 1 - Через браузер (рекомендуется):
 * ==========================================
 * 1. Откройте: http://your-nas-ip/generate_password.php
 * 2. Введите имя пользователя (например: john)
 * 3. Введите пароль (например: MySecurePass123)
 * 4. Нажмите "Generate Hash"
 * 5. Скопируйте готовую строку для config.php
 * 6. УДАЛИТЕ этот файл!
 * 
 * СПОСОБ 2 - Через командную строку:
 * ===================================
 * $ php generate_password.php
 * Enter username: john
 * Enter password: MySecurePass123
 * 
 * === Add to config.php ===
 * 'john' => '$2y$10$abcdefghijklmnopqrstuvwxyz...',
 * 
 * ПРИМЕР:
 * =======
 * Вы хотите создать пользователя "john" с паролем "john2024"
 * 
 * 1. Запустите эту утилиту
 * 2. Введите username: john
 * 3. Введите password: john2024
 * 4. Получите готовую строку:
 *    'john' => '$2y$10$xyz...',  // john2024
 * 5. Скопируйте и вставьте в config.php:
 *    $users = [
 *        'admin' => '$2y$10$...',
 *        'john' => '$2y$10$xyz...',  // john2024
 *    ];
 * 6. Теперь можно войти как john с паролем john2024
 */

// Проверка запуска из командной строки
$isCLI = php_sapi_name() === 'cli';

if ($isCLI) {
    echo "=== Password Hash Generator ===\n\n";
    
    echo "Enter username: ";
    $username = trim(fgets(STDIN));
    
    if (empty($username)) {
        echo "Error: Username cannot be empty\n";
        exit(1);
    }
    
    echo "Enter password: ";
    $password = trim(fgets(STDIN));
    
    if (empty($password)) {
        echo "Error: Password cannot be empty\n";
        exit(1);
    }
    
    $hash = password_hash($password, PASSWORD_DEFAULT);
    echo "\n=== Generated Hash ===\n";
    echo $hash . "\n\n";
    
    echo "=== Add to config.php ===\n";
    echo "'" . $username . "' => '" . $hash . "',\n\n";
    
    echo "=== Complete Example ===\n";
    echo "\$users = [\n";
    echo "    'admin' => '\$2y\$10\$...',  // existing user\n";
    echo "    '" . $username . "' => '" . $hash . "',  // " . $password . "\n";
    echo "];\n\n";
    
    echo "✅ Login with username: " . $username . " and your password\n";
    
} else {
    // Веб-интерфейс
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Password Hash Generator</title>
        <style>
            * {
                margin: 0;
                padding: 0;
                box-sizing: border-box;
            }
            body {
                font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
                background: #f5f5f5;
                padding: 2rem;
                min-height: 100vh;
                display: flex;
                align-items: center;
                justify-content: center;
            }
            .container {
                background: white;
                padding: 2rem;
                border-radius: 8px;
                box-shadow: 0 2px 8px rgba(0,0,0,0.1);
                max-width: 600px;
                width: 100%;
            }
            h1 {
                margin-bottom: 1.5rem;
                color: #333;
            }
            .form-group {
                margin-bottom: 1.5rem;
            }
            label {
                display: block;
                margin-bottom: 0.5rem;
                color: #555;
                font-weight: 500;
            }
            input[type="password"],
            input[type="text"] {
                width: 100%;
                padding: 0.75rem;
                border: 2px solid #ddd;
                border-radius: 4px;
                font-size: 1rem;
            }
            input:focus {
                outline: none;
                border-color: #3498db;
            }
            button {
                background: #3498db;
                color: white;
                border: none;
                padding: 0.75rem 1.5rem;
                border-radius: 4px;
                font-size: 1rem;
                cursor: pointer;
                transition: background 0.3s;
            }
            button:hover {
                background: #2980b9;
            }
            .result {
                margin-top: 1.5rem;
                padding: 1rem;
                background: #f8f9fa;
                border-radius: 4px;
                display: none;
            }
            .result.show {
                display: block;
            }
            .hash {
                background: #fff;
                padding: 1rem;
                border: 1px solid #ddd;
                border-radius: 4px;
                word-break: break-all;
                font-family: monospace;
                margin: 1rem 0;
            }
            .code {
                background: #2c3e50;
                color: #ecf0f1;
                padding: 1rem;
                border-radius: 4px;
                font-family: monospace;
                font-size: 0.9rem;
                overflow-x: auto;
            }
            .warning {
                background: #fff3cd;
                border: 1px solid #ffc107;
                color: #856404;
                padding: 1rem;
                border-radius: 4px;
                margin-bottom: 1.5rem;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <h1>Password Hash Generator</h1>
            
            <div class="warning">
                ⚠️ <strong>Security Warning:</strong> Delete this file after generating your password hashes!
            </div>
            
            <form method="POST">
                <div class="form-group">
                    <label for="username">Username:</label>
                    <input type="text" id="username" name="username" required placeholder="e.g., john">
                </div>
                
                <div class="form-group">
                    <label for="password">Enter Password:</label>
                    <input type="password" id="password" name="password" required placeholder="Enter a strong password">
                </div>
                
                <button type="submit">Generate Hash</button>
            </form>
            
            <?php
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['password'])) {
                $username = $_POST['username'] ?? 'username';
                $password = $_POST['password'];
                $hash = password_hash($password, PASSWORD_DEFAULT);
                ?>
                <div class="result show">
                    <h3>Generated Hash:</h3>
                    <div class="hash"><?php echo htmlspecialchars($hash); ?></div>
                    
                    <h3>Add to config.php:</h3>
                    <div class="code">'<?php echo htmlspecialchars($username); ?>' => '<?php echo htmlspecialchars($hash); ?>',</div>
                    
                    <h3>Complete Example:</h3>
                    <div class="code">$users = [
    'admin' => '$2y$10$...',  // existing user
    '<?php echo htmlspecialchars($username); ?>' => '<?php echo htmlspecialchars($hash); ?>',  // <?php echo htmlspecialchars($password); ?>

];</div>
                    
                    <p style="margin-top: 1rem; color: #666; font-size: 0.9rem;">
                        ✅ Copy the line above and add it to the $users array in config.php<br>
                        🔐 Login with username: <strong><?php echo htmlspecialchars($username); ?></strong> and your password
                    </p>
                </div>
                <?php
            }
            ?>
        </div>
    </body>
    </html>
    <?php
}
