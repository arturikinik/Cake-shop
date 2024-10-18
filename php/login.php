<?php
session_start();
$host = '127.0.0.1';
$port = '3307';
$dbname = 'cake_shop';
$username = 'root';
$password = '';
$conn = new PDO("mysql:host=$host;port=$port;dbname=$dbname", $username, $password);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Проверяем пользователя в базе данных
    $sql = "SELECT * FROM users WHERE username = :username";
    $stmt = $conn->prepare($sql);
    $stmt->execute(['username' => $username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['role']; // Сохраняем роль в сессии
        $_SESSION['username'] = $user['username'];
        $_SESSION['phone'] = $user['phone']; 
        // Перенаправляем в личный кабинет
        if ($user['role'] == 'admin') {
            header("Location: admin_dashboard.php");
        } else {
            header("Location: user_dashboard.php");
        }
        exit();
    } else {
        echo "Неверное имя пользователя или пароль.";
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../css/stylephp.css">
    <title>Вход</title>
</head>
<body>
    <form method="POST" action="login.php">
        <label>Имя пользователя:</label>
        <input type="text" name="username" required>
        <label>Пароль:</label>
        <input type="password" name="password" required>
        <button type="submit">Войти</button>
        <a href="../index.php">На главную</a>
    </form>
</body>
</html>
