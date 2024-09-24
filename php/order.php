<?php
// Конфигурация базы данных
$host = '127.0.0.1'; 
$port = '3307'; 
$dbname = 'cake_shop'; 
$username = 'root'; 
$password = ''; 

// Подключение к базе данных
try {
    $conn = new PDO("mysql:host=$host;port=$port;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Ошибка подключения к базе данных: " . $e->getMessage());
}

// Проверка, что запрос POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Получаем данные из формы
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $delivery_date = $_POST['date'];
    $comment = $_POST['comment'];
    $cartData = $_POST['cartData']; // Данные корзины в формате JSON

    // Проверка существования пользователя
    $sql = "SELECT * FROM users WHERE username = :username";
    $stmt = $conn->prepare($sql);
    $stmt->execute(['username' => $name]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Если пользователь не найден, регистрируем его
    if (!$user) {
        $passwordHash = password_hash($phone, PASSWORD_DEFAULT); // Пароль - телефон
        $sql = "INSERT INTO users (username, password) VALUES (:username, :password)";
        $stmt = $conn->prepare($sql);
        $stmt->execute(['username' => $name, 'password' => $passwordHash]);
        $userId = $conn->lastInsertId();
    } else {
        $userId = $user['id'];
    }

    // Вставляем основной заказ в таблицу orders
    try {
        $sql = "INSERT INTO orders (user_id, name, phone, delivery_date, comment) VALUES (:user_id, :name, :phone, :delivery_date, :comment)";
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            'user_id' => $userId,
            'name' => $name,
            'phone' => $phone,
            'delivery_date' => $delivery_date,
            'comment' => $comment
        ]);

        // Получаем ID последнего заказа
        $orderId = $conn->lastInsertId();

        // Вставляем каждый элемент корзины в таблицу order_items
        $cart = json_decode($cartData, true); // Преобразуем JSON-корзину в массив
        foreach ($cart as $item) {
            $sql = "INSERT INTO order_items (order_id, cake_name, quantity) VALUES (:order_id, :cake_name, :quantity)";
            $stmt = $conn->prepare($sql);
            $stmt->execute([
                'order_id' => $orderId,
                'cake_name' => $item['name'],
                'quantity' => $item['quantity']
            ]);
        }

        echo "Ваш заказ успешно оформлен!";
        header("Location: ../index.html");
    } catch (PDOException $e) {
        die("Ошибка при оформлении заказа: " . $e->getMessage());
    }
}
?>



