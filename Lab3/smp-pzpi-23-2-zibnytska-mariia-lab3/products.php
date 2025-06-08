<?php
require_once 'functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'])) {
    $success = true;
    $errors = [];
    
    foreach ($_POST as $key => $value) {
        if (strpos($key, 'quantity_') === 0) {
            $product_id = str_replace('quantity_', '', $key);
            $quantity = (int)$value;
            
            if ($quantity < 0 || $quantity > 99) {
                $errors[] = "Кількість товару має бути від 0 до 99";
                $success = false;
            } else {
                addToCart($product_id, $quantity);
            }
        }
    }
    
    if ($success && empty($errors)) {
        header('Location: cart.php');
        exit;
    }
}

$products = getAllProducts();
include 'header.php';
?>

<div class="container">
    <h1>Наші товари</h1>
    
    <?php if (!empty($errors)): ?>
        <div class="error-message">
            <strong>Перевірте будь ласка введені дані:</strong>
            <ul style="margin: 10px 0 0 20px;">
                <?php foreach ($errors as $error): ?>
                    <li><?= htmlspecialchars($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>
    
    <form method="POST" action="products.php">
        <div class="products-grid">
            <?php foreach ($products as $product): ?>
                <?php $cartQuantity = getProductQuantityInCart($product['id']); ?>
                <div class="product-item">
                    <div class="product-image">
                        <img src="images/<?= htmlspecialchars($product['image']) ?>" 
                             alt="<?= htmlspecialchars($product['name']) ?>"
                             onerror="this.src='images/no-image.jpg'">
                    </div>
                    
                    <div class="product-info">
                        <h3><?= htmlspecialchars($product['name']) ?></h3>
                        <p class="price"><?= number_format($product['price'], 0) ?> грн</p>
                        
                        <div class="quantity-control">
                            <label for="quantity_<?= $product['id'] ?>">Кількість:</label>
                            <input type="number" 
                                   id="quantity_<?= $product['id'] ?>" 
                                   name="quantity_<?= $product['id'] ?>" 
                                   value="<?= $cartQuantity ?>" 
                                   min="0" 
                                   max="99" 
                                   class="quantity-input">
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        
        <div style="text-align: center; margin-top: 30px;">
            <button type="submit" name="add_to_cart" class="btn-primary">
                Додати обрані товари до кошика
            </button>
        </div>
    </form>
</div>

<?php include 'footer.php'; ?>