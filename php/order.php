<?php
// Стартуем сессию для уведомлений
session_start();

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
    // Получаем и валидируем данные из формы
    $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
    $phone = filter_input(INPUT_POST, 'phone', FILTER_SANITIZE_STRING);
    $delivery_date = filter_input(INPUT_POST, 'date', FILTER_SANITIZE_STRING);
    $comment = filter_input(INPUT_POST, 'comment', FILTER_SANITIZE_STRING);
    $cartData = $_POST['cartData']; // Данные корзины в формате JSON

    // Проверка, что логин или имя содержат только английские буквы
    if (!preg_match("/^[a-zA-Z]+$/", $name)) {
        $_SESSION['message'] = "Логин или имя могут содержать только английские буквы.";
        header("Location: ../index.php");
        exit();
    }

    // Проверка существования пользователя по имени (уникальность)
    $sql = "SELECT * FROM users WHERE username = :username";
    $stmt = $conn->prepare($sql);
    $stmt->execute(['username' => $name]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        $_SESSION['message'] = "Пользователь с таким именем уже существует.";
        header("Location: ../index.php");
        exit();
    }

    if (empty($delivery_date)) {
        $_SESSION['message'] = "Дата доставки обязательна для заполнения!";
        header("Location: ../index.php");
        exit();
    }

    // Проверка существования пользователя по телефону
    $sql = "SELECT * FROM users WHERE phone = :phone";
    $stmt = $conn->prepare($sql);
    $stmt->execute(['phone' => $phone]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Если пользователь не найден, регистрируем его
    if (!$user) {
        // Получаем введённый пользователем пароль
        $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);
        
        // Проверка, что пароль введен
        if (empty($password)) {
            $_SESSION['message'] = "Пароль обязателен для заполнения!";
            header("Location: ../index.php");
            exit();
        }
        
        // Хэшируем введённый пароль
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        
        // Добавляем пользователя в базу данных
        try {
            $sql = "INSERT INTO users (username, phone, password) VALUES (:username, :phone, :password)";
            $stmt = $conn->prepare($sql);
            $stmt->execute(['username' => $name, 'phone' => $phone, 'password' => $passwordHash]);
            $userId = $conn->lastInsertId(); // Получаем ID нового пользователя
            
            // Проверим, добавился ли пользователь
            if ($userId) {
                $_SESSION['message'] = "Регистрация прошла успешно! Ваш заказ оформлен.";
            } else {
                $_SESSION['message'] = "Ошибка при регистрации пользователя.";
                header("Location: ../index.php");
                exit();
            }
        } catch (PDOException $e) {
            $_SESSION['message'] = "Ошибка при добавлении пользователя: " . $e->getMessage();
            header("Location: ../index.php");
            exit();
        }
    } else {
        // Пользователь существует
        $userId = $user['id']; // Идентификатор пользователя из БД
        $_SESSION['message'] = "Ваш заказ успешно оформлен!";
    }

    try {
        // Вставляем основной заказ в таблицу orders
        $sql = "INSERT INTO orders (user_id, name, phone, delivery_date, comment) VALUES (:user_id, :name, :phone, :delivery_date, :comment)";
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            'user_id' => $userId,
            'name' => $name,
            'phone' => $phone, // Используем номер телефона
            'delivery_date' => $delivery_date,
            'comment' => $comment
        ]);
    
        // Если заказ успешно добавлен
        $orderId = $conn->lastInsertId();
    
        // Преобразуем JSON-корзину в массив
        $cart = json_decode($cartData, true); 
    
        // Проверяем, что корзина не пуста
        if (!empty($cart)) {
            // Вставляем каждый элемент корзины в таблицу order_items
            foreach ($cart as $item) {
                if (isset($item['name'], $item['quantity'])) {
                    $sql = "INSERT INTO order_items (order_id, cake_name, quantity) VALUES (:order_id, :cake_name, :quantity)";
                    $stmt = $conn->prepare($sql);
                    $stmt->execute([
                        'order_id' => $orderId,
                        'cake_name' => $item['name'],
                        'quantity' => $item['quantity']
                    ]);
                }
            }
        }
    
        // Переадресация на главную страницу с выводом сообщения
        header("Location: ../index.php");
        exit();
    } catch (PDOException $e) {
        // Если возникает ошибка, сохраняем ее в сессии
        $_SESSION['message'] = "Ошибка при оформлении заказа: " . $e->getMessage();
        header("Location: ../index.php");
        exit();
    }
}
?>


