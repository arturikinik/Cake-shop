<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'user') {
    header("Location: login.php");
    exit();
}

$conn = new PDO("mysql:host=127.0.0.1;port=3307;dbname=cake_shop", 'root', '');

// SQL-запрос для получения заказов пользователя с тортами и их количеством
$sql = "
    SELECT o.id, o.delivery_date, o.comment, o.status, 
           i.cake_name, i.quantity
    FROM orders o
    LEFT JOIN order_items i ON o.id = i.order_id
    WHERE o.user_id = :user_id
    ORDER BY o.id
";


$stmt = $conn->prepare($sql);
$stmt->execute(['user_id' => $_SESSION['user_id']]);
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Мои заказы</title>
    <link rel="stylesheet" href="../css/stylephp.css">
</head>
<body>
    <h1>Мои заказы</h1>
    <table border="1">
        <tr>
            <th>Дата доставки</th>
            <th>Комментарий</th>
            <th>Название торта</th>
            <th>Количество</th>
            <th>статус</th>
        </tr>
        <?php foreach ($orders as $order): ?>
        <tr>
            <td><?= $order['delivery_date'] ?></td>
            <td><?= $order['comment'] ?></td>
            <td><?= $order['cake_name'] ?></td>
            <td><?= $order['quantity'] ?></td>
            <td><?= $order['status'] ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
    <a href="logout.php">Выйти</a>
    <a href="../index.php">На главную</a>
</body>
</html>

