<?php
session_start();
session_destroy(); // Завершаем сессию
header("Location: login.php"); // Перенаправляем на страницу входа
exit();
?>
