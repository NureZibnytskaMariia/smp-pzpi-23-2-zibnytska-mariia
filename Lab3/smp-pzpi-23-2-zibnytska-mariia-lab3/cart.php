<?php
require_once 'functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'remove':
                if (isset($_POST['product_id'])) {
                    removeFromCart($_POST['product_id']);
                    header('Location: cart.php');
                    exit;
                }
                break;
            case 'clear':
                clearCart();
                header('Location: cart.php');
                exit;
                break;
            case 'checkout':
                $checkoutItems = getCartItems();
                $checkoutTotal = getCartTotal();
                clearCart();
                $show_receipt = true;
                break;
        }
    }
}

$cartItems = getCartItems();
$cartTotal = getCartTotal();
include 'header.php';
?>

<div class="container">
    <h1>Мій кошик</h1>
    
    <?php if (isset($show_receipt) && !empty($checkoutItems)): ?>
        <div style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000;">
            <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background: white; padding: 20px; border: 1px solid #ccc; width: 400px; max-height: 80%; overflow-y: auto;">
                <div style="text-align: right; margin-bottom: 10px;">
                    <a href="cart.php" style="text-decoration: none; font-size: 20px; font-weight: bold; color: #666;">&times;</a>
                </div>
                
                <div style="text-align: center; margin-bottom: 20px;">
                    <h3>ЧЕК</h3>
                    <p>Продовольчий магазин "Весна"</p>
                    <p><?= date('d.m.Y H:i') ?></p>
                </div>
                
                <div>
                    <?php $itemNumber = 1; ?>
                    <?php foreach ($checkoutItems as $item): ?>
                        <div style="border-bottom: 1px dotted #ccc; padding: 5px 0;">
                            <div><?= $itemNumber ?>. <?= htmlspecialchars($item['name']) ?></div>
                            <div style="display: flex; justify-content: space-between;">
                                <span><?= $item['quantity'] ?> x <?= number_format($item['price'], 0) ?> грн</span>
                                <span><?= number_format($item['total'], 0) ?> грн</span>
                            </div>
                        </div>
                        <?php $itemNumber++; ?>
                    <?php endforeach; ?>
                    
                    <div style="margin-top: 15px; text-align: right; font-weight: bold; font-size: 18px;">
                        СУМА: <?= number_format($checkoutTotal, 0) ?> грн
                    </div>
                </div>
                
                <div style="text-align: center; margin-top: 20px;">
                    <p>Дякуємо за покупку!</p>
                    <p>Ваше замовлення успішно оформлено.</p>
                </div>
            </div>
        </div>
    <?php endif; ?>
    
    <?php if (empty($cartItems)): ?>
        <div class="empty-cart">
            <h2>Ваш кошик порожній</h2>
            <p>Додайте товари до кошика, щоб продовжити покупки</p>
            <a href="products.php" class="btn-primary">
                Перейти до покупок
            </a>
        </div>
    <?php else: ?>
        <div class="cart-content">
            <table class="cart-table">
                <thead>
                    <tr>
                        <th>№</th>
                        <th>Назва</th>
                        <th>Ціна</th>
                        <th>Кількість</th>
                        <th>Вартість</th>
                        <th>Дії</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $counter = 1; ?>
                    <?php foreach ($cartItems as $item): ?>
                        <tr>
                            <td><?= $counter ?></td>
                            <td>
                                <div class="product-info-cart">
                                    <img src="images/<?= htmlspecialchars($item['image']) ?>"
                                         alt="<?= htmlspecialchars($item['name']) ?>"
                                         onerror="this.src='images/no-image.jpg'"
                                         class="cart-product-image">
                                    <?= htmlspecialchars($item['name']) ?>
                                </div>
                            </td>
                            <td><?= number_format($item['price'], 0) ?> грн</td>
                            <td><?= $item['quantity'] ?></td>
                            <td><?= number_format($item['total'], 0) ?> грн</td>
                            <td>
                                <form method="POST" style="display: inline;">
                                    <input type="hidden" name="action" value="remove">
                                    <input type="hidden" name="product_id" value="<?= $item['product_id'] ?>">
                                    <button type="submit" class="btn-remove" onclick="return confirm('Видалити товар з кошика?')">
                                        Видалити
                                    </button>
                                </form>
                            </td>
                        </tr>
                        <?php $counter++; ?>
                    <?php endforeach; ?>
                </tbody>
            </table>
            
            <div class="cart-total">
                <h2>РАЗОМ ДО СПЛАТИ: <?= number_format($cartTotal, 0) ?> грн</h2>
            </div>
            
            <div class="cart-actions">
                <form method="POST" style="display: inline;">
                    <input type="hidden" name="action" value="clear">
                    <button type="submit" class="btn-secondary" onclick="return confirm('Очистити весь кошик?')">
                        Очистити кошик
                    </button>
                </form>
                
                <a href="products.php" class="btn-secondary">
                    Продовжити покупки
                </a>
                
                <form method="POST" style="display: inline;">
                    <input type="hidden" name="action" value="checkout">
                    <button type="submit" class="btn-primary">
                        Оформити замовлення
                    </button>
                </form>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php include 'footer.php'; ?>