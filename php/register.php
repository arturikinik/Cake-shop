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
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Хешируем пароль
    // Вставка пользователя
    $sql = "INSERT INTO users (username, password) VALUES (:username, :password)";
    $stmt = $conn->prepare($sql);
    $stmt->execute(['username' => $username, 'password' => $password]);

    echo "Регистрация прошла успешно!";
    header("Location: ../index.php"); // Перенаправляем на главную страницу
    exit();
};
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../css/stylephp.css">
    <title>Регистрация</title>
</head>
<body>
    <form method="POST" action="register.php">
        <label>Имя пользователя:</label>
        <input type="text" name="username" required>
        <label>Пароль:</label>
        <input type="password" name="password" required>
        <button type="submit">Зарегистрироваться</button>
        <a href="../index.php">На главную</a>
    </form>
    
</body>
</html>
