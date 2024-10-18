let cart = [];

// Функция для добавления торта в корзину
function orderCake(cakeName) {
    const existingItem = cart.find(item => item.name === cakeName);

    if (existingItem) {
        existingItem.quantity += 1;
    } else {
        cart.push({ name: cakeName, quantity: 1 });
    }
    
    updateCart();
    window.scrollTo(0, document.getElementById('order').offsetTop); // Прокручиваем к форме заказа
}

// Обновление корзины на странице
function updateCart() {
    const cartContainer = document.getElementById('cart');
    cartContainer.innerHTML = ''; // Очищаем старую корзину

    if (cart.length === 0) {
        cartContainer.innerHTML = '<p>Корзина пуста</p>';
        return;
    }

    cart.forEach((item, index) => {
        const cartItem = document.createElement('div');
        cartItem.classList.add('cart-item');
        cartItem.innerHTML = `
            <span>${item.name} (x${item.quantity})</span>
            <button onclick="removeFromCart(${index})">Удалить</button>
        `;
        cartContainer.appendChild(cartItem);
    });

    // Сохраняем данные корзины для отправки на сервер
    document.getElementById('cartData').value = JSON.stringify(cart);
}

// Удаление товара из корзины
function removeFromCart(index) {
    cart.splice(index, 1);
    updateCart();
}

// Валидация данных перед отправкой формы
document.addEventListener('DOMContentLoaded', function() {
    // Ограничение выбора даты - только будущие даты
    var today = new Date().toISOString().split('T')[0];
    document.getElementById("date").setAttribute('min', today);

    // Валидация номера телефона и имени при отправке формы
    var form = document.querySelector('form');
    form.addEventListener('submit', function(event) {
        var phoneInput = document.getElementById('phone').value;
        // Новый шаблон для проверки номера телефона с учётом формата
        var phonePattern = /^\+7 \(\d{3}\) \d{3}-\d{2}-\d{2}$/;

        // Проверка номера телефона
        if (!phonePattern.test(phoneInput)) {
            alert('Пожалуйста, введите корректный номер телефона (например, +7 (923) 755-46-56)');
            event.preventDefault(); // Останавливаем отправку формы
        }

        // Валидация имени пользователя (только английские буквы)
        var nameInput = document.getElementById('name').value;
        var namePattern = /^[a-zA-Z]+$/;

        if (!namePattern.test(nameInput)) {
            alert('Имя должно содержать только английские буквы.');
            event.preventDefault(); // Останавливаем отправку формы
        }
    });
});


// Автоскрытие уведомления
setTimeout(function() {
    var messageDiv = document.getElementById('message');
    if (messageDiv) {
        messageDiv.style.display = 'none';
    }
}, 5000); // Скрыть сообщение через 5 секунд









