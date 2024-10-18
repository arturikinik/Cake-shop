<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

$conn = new PDO("mysql:host=127.0.0.1;port=3307;dbname=cake_shop", 'root', '');

// SQL-запрос для получения всех заказов с тортами и их количеством
$sql = "
    SELECT o.id, o.name, o.phone, o.delivery_date, o.comment, 
           o.status, i.cake_name, i.quantity
    FROM orders o
    LEFT JOIN order_items i ON o.id = i.order_id
    ORDER BY o.name, o.id
";

$stmt = $conn->query($sql);
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../css/stylephp.css">
    <title>Все заказы</title>
</head>
<body>
    <h1>Все заказы</h1>
    <table border="1">
        <tr>
            <th>ID заказа</th>
            <th>Имя</th>
            <th>Телефон</th>
            <th>Название торта</th>
            <th>Количество</th>
            <th>Дата доставки</th>
            <th>Комментарий</th>
            <th>Статус</th>
            <th>Изменить статус</th>
        </tr>
        
        <?php 
        $currentUser = null; // Текущий пользователь
        $previousUser = null; // Предыдущий пользователь, чтобы отслеживать смену

        foreach ($orders as $order): 
            // Проверяем, сменился ли пользователь
            if ($currentUser !== $order['name']):
                if ($previousUser !== null) {
                    // Вставляем пустую строку после предыдущего пользователя
                    echo '<tr><td colspan="9" style="background-color: #fff;">&nbsp;</td></tr>';
                }

                $currentUser = $order['name']; // Обновляем текущего пользователя
                $previousUser = $currentUser;
        ?>
        <!-- Строка для нового пользователя -->
        <tr style="background-color: #f9f9f9;">
            <td><?= $order['id'] ?></td>
            <td><?= $order['name'] ?></td>
            <td><?= $order['phone'] ?></td>
            <td><?= $order['cake_name'] ?></td>
            <td><?= $order['quantity'] ?></td>
            <td><?= $order['delivery_date'] ?></td>
            <td><?= $order['comment'] ?></td>
            <td><?= $order['status'] ?></td>
            <td>
            <form action="update_order_status.php" method="post">
                <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
                <div class="custom-select-wrapper">
                <select name="status">
                    <option value="Обрабатываем заказ" <?= $order['status'] == 'Обрабатываем заказ' ? 'selected' : '' ?>>Обрабатываем заказ</option>
                    <option value="Готовим заказ" <?= $order['status'] == 'Готовим заказ' ? 'selected' : '' ?>>Готовим заказ</option>
                    <option value="В пути" <?= $order['status'] == 'В пути' ? 'selected' : '' ?>>В пути</option>
                    <option value="Доставлено" <?= $order['status'] == 'Доставлено' ? 'selected' : '' ?>>Доставлено</option>
                </select>
                </div>
                <button type="submit">Изменить статус</button>
            </form>
            </td>
        </tr>
        <?php else: ?>
        <!-- Строка для того же пользователя (без дублирования имени и телефона) -->
        <tr>
            <td><?= $order['id'] ?></td>
            <td></td> <!-- Пустая ячейка для имени -->
            <td></td> <!-- Пустая ячейка для телефона -->
            <td><?= $order['cake_name'] ?></td>
            <td><?= $order['quantity'] ?></td>
            <td><?= $order['delivery_date'] ?></td>
            <td><?= $order['comment'] ?></td>
            <td><?= $order['status'] ?></td>
            <td>
            <form action="update_order_status.php" method="post">
                <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
                <div class="custom-select-wrapper">
                <select name="status">
                    <option value="Обрабатываем заказ" <?= $order['status'] == 'Обрабатываем заказ' ? 'selected' : '' ?>>Обрабатываем заказ</option>
                    <option value="Готовим заказ" <?= $order['status'] == 'Готовим заказ' ? 'selected' : '' ?>>Готовим заказ</option>
                    <option value="В пути" <?= $order['status'] == 'В пути' ? 'selected' : '' ?>>В пути</option>
                    <option value="Доставлено" <?= $order['status'] == 'Доставлено' ? 'selected' : '' ?>>Доставлено</option>
                </select>
                </div>
                <button type="submit">Изменить статус</button>
            </form>
            </td>
        </tr>
        <?php endif; ?>
        <?php endforeach; ?>

        <!-- Добавляем пустую строку после последнего пользователя -->
        <tr><td colspan="9" style="background-color: #fff;">&nbsp;</td></tr>
    </table>
    <a href="logout.php">Выйти</a>
    <a href="../index.php">На главную</a>
</body>
</html>






