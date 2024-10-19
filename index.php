<?php
session_start();
var_dump($_SESSION); // Для отладки, чтобы проверить сессию

$isLoggedIn = isset($_SESSION['user_id']);
$isAdmin = isset($_SESSION['role']) && $_SESSION['role'] == 'admin'; // Проверка на админа
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Торт на заказ Барнаул</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <!-- Уведомление пользователя -->
    <?php if (isset($_SESSION['message'])): ?>
        <div class="alert" id="message">
            <?php 
                echo $_SESSION['message']; 
                unset($_SESSION['message']); // Удаляем сообщение после вывода
            ?>
        </div>
    <?php endif; ?>
    <!-- Шапка сайта -->
    <nav id="navbar">
        <div class="nav-container">
            <ul>
                <li><a href="#about">О нас</a></li>
                <li><a href="#gallery">Галерея</a></li>
                <li><a href="#order">Заказ</a></li>
                <?php if ($isLoggedIn): ?>
                    <li><a href="php/logout.php">Выйти</a></li>
                    <?php if ($isAdmin): ?>
                        <li><a href="php/admin_dashboard.php">Управление</a></li> <!-- Кнопка для админа -->
                    <?php else: ?>
                        <li><a href="php/user_dashboard.php">Мои заказы</a></li>
                    <?php endif; ?> <!-- Закрываем блок для проверки админа -->
                <?php else: ?>
                    <li><a href="php/login.php">Войти</a></li>
                    <li><a href="php/register.php">Зарегистрироваться</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </nav>

    <!-- Основное содержимое -->
    <header>
        <h1>Торт на заказ Барнаул</h1>
        <p>Ваш идеальный торт для любого праздника!</p>
    </header>

    <!-- Блок О нас -->
    <section id="about">
        <h2>О нас</h2>
        <p>
            Наши торты — это больше, чем просто десерт; это съедобные произведения искусства. 
            Каждый торт изготавливается из самых свежих, лучших ингредиентов и выпекается до совершенства. 
            Наша команда кондитеров постоянно внедряет инновации, создавая новые и захватывающие вкусы и дизайны. 
            Мы хотим сделать наших клиентов счастливыми и стремимся создать идеальный торт для любого случая. 
            Ищете ли вы торт на день рождения, свадебный торт или просто особое угощение, у нас есть идеальный торт для вас.
        </p>

        <h3>Наши преимущества</h3>
        <div class="advantages">
            <div class="advantage-item">
                <img src="images/recept.png" alt="Авторские рецепты">
                <h4>Авторские рецепты</h4>
                <p>Мы предлагаем уникальные авторские вкусы, которые не оставят вас равнодушными.</p>
            </div>

            <div class="advantage-item">
                <img src="images/dostavka.png" alt="Быстрая доставка">
                <h4>Быстрая доставка на дом</h4>
                <p>Наши курьеры доставят ваш торт быстро и аккуратно прямо к вашему порогу.</p>
            </div>

            <div class="advantage-item">
                <img src="images/loyalty.png" alt="Программа лояльности">
                <h4>Программа лояльности</h4>
                <p>Заказывайте чаще и получайте скидки!</p>
            </div>
        </div>
    </section>

    <section id="gallery">
        <h2>Галерея тортов</h2>
        <div class="cake-list">
            <!-- Торт 1 -->
            <div class="cake-item">
                <div class="cake-image">
                    <img src="images/choco.jpg" alt="Торт 1">
                </div>
                <h3>Торт "Шоколадное наслаждение"</h3>
                <p>Шоколадный бисквит с насыщенной шоколадной начинкой и глазурью.</p>
                <button onclick="orderCake('Торт Шоколадное наслаждение')">Заказать</button>
            </div>

            <!-- Торт 2 -->
            <div class="cake-item">
                <div class="cake-image">
                    <img src="images/strawberry.jpg" alt="Торт 2">
                </div>
                <h3>Торт "Клубничный рай"</h3>
                <p>Легкий ванильный бисквит с клубничным кремом и свежими ягодами.</p>
                <button onclick="orderCake('Торт Клубничный рай')">Заказать</button>
            </div>

            <!-- Торт 3 -->
            <div class="cake-item">
                <div class="cake-image">
                    <img src="images/honey.jpg" alt="Торт 3">
                </div>
                <h3>Торт "Медовый"</h3>
                <p>Классический медовик с нежным сметанным кремом.</p>
                <button onclick="orderCake('Торт Медовый')">Заказать</button>
            </div>

            <!-- Торт 4 -->
            <div class="cake-item">
                <div class="cake-image">
                    <img src="images/fruct.jpg" alt="Торт 4">
                </div>
                <h3>Торт "Фруктовый микс"</h3>
                <p>Освежающий бисквит с фруктами, наполненный легким кремом.</p>
                <button onclick="orderCake('Торт Фруктовый микс')">Заказать</button>
            </div>

            <!-- Торт 5 -->
            <div class="cake-item">
                <div class="cake-image">
                    <img src="images/3choc.jpg" alt="Торт 5">
                </div>
                <h3>Торт "Три шоколада"</h3>
                <p>Нежное сочетание белого, молочного и темного шоколада в одном торте.</p>
                <button onclick="orderCake('Торт Три шоколада')">Заказать</button>
            </div>

            <!-- Торт 6 -->
            <div class="cake-item">
                <div class="cake-image">
                    <img src="images/orex.jpg" alt="Торт 6">
                </div>
                <h3>Торт "Ореховый сюрприз"</h3>
                <p>Шоколадный бисквит с грецкими орехами и карамелью.</p>
                <button onclick="orderCake('Торт Ореховый сюрприз')">Заказать</button>
            </div>
        </div>
    </section>

    <section id="order">
        <h2>Оформление заказа</h2>
        <?php if ($isLoggedIn): ?>
            <!-- Форма только для заказа -->
            <form action="php/order.php" method="post">
            <input type="hidden" name="name" value="<?php echo htmlspecialchars($_SESSION['username']); ?>">
            <input type="hidden" name="phone" value="<?php echo htmlspecialchars($_SESSION['phone']); ?>">


                <label for="date">Дата доставки:</label>
                <input type="date" id="date" name="date" required>

                <label for="comment">Комментарий к заказу:</label>
                <textarea id="comment" name="comment" placeholder="Введите пожелания по заказу" rows="3"></textarea>

                <h3>Корзина</h3>
                <div id="cart">
                    <p>Корзина пуста</p>
                </div>

                <input type="hidden" id="cartData" name="cartData">
                <button type="submit" class="submit-btn">Оформить заказ</button>
            </form>
            <?php else: ?>
                <!-- Форма для регистрации и заказа -->
                <form action="php/order.php" method="post">
                    <label for="name">Ваш логин:(только латинские символы)</label>
                    <input type="text" id="name" name="name" required>

                    <label for="phone">Телефон:</label>
                    <label for="phone">Телефон:</label>
                    <input type="tel" id="phone" name="phone" placeholder="+7 (9XX) XXX-XX-XX">
                    <label for="password">Пароль:</label>
                    <input type="password" id="password" name="password" required>

                    <label for="date">Дата доставки:</label>
                    <input type="date" id="date" name="date" required>

                    <label for="comment">Комментарий к заказу:</label>
                    <textarea id="comment" name="comment" placeholder="Введите пожелания по заказу" rows="3"></textarea>

                    <h3>Корзина</h3>
                    <div id="cart">
                        <p>Корзина пуста</p>
                    </div>

                    <input type="hidden" id="cartData" name="cartData">
                    <button type="submit" class="submit-btn">Зарегистрироваться и оформить заказ</button>
                </form>
            <?php endif; ?>
    </section>
    <!-- Подключение jQuery -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <!-- Подключение Inputmask -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/5.0.7/jquery.inputmask.min.js"></script>
    <script>
        $(document).ready(function(){
            $("#phone").inputmask({
                mask: "+7 (999) 999-99-99",
                placeholder: " "
            });
        });
    </script>
    <!-- Подключение моих скриптов -->          
    <script src="js/script.js"></script>
</body>
</html>

