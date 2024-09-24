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
    window.scrollTo(0, document.getElementById('order').offsetTop);
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
