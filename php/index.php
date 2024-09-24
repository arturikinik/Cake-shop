<?php
session_start();

// Проверяем, авторизован ли пользователь
if (isset($_SESSION['user_id'])) {
    // Перенаправляем в зависимости от роли
    if ($_SESSION['role'] == 'admin') {
        header("Location: admin_dashboard.php"); // Перенаправляем администратора
        exit();
    } else {
        header("Location: user_dashboard.php"); // Перенаправляем обычного пользователя
        exit();
    }
} else {
    // Если не авторизован, перенаправляем на страницу авторизации
    header("Location: login.php");
    exit();
}
?>
