<?php
require_once 'functions.php';
include 'header.php';
?>

<div class="container">
    <h1>Вітаємо в продовольчому магазині "Весна"!</h1>
    
    <div style="text-align: center; margin: 40px 0;">
        <p style="font-size: 18px; margin-bottom: 30px; color: #555;">
            У нашому магазині ви знайдете найсвіжіші та найякісніші продукти за доступними цінами.
        </p>
        
        <div style="display: flex; justify-content: center; gap: 20px; margin: 30px 0; flex-wrap: wrap;">
            <a href="products.php" class="btn-primary">
                Переглянути товари
            </a>
            <a href="cart.php" class="btn-secondary">
                Мій кошик
            </a>
        </div>
    </div>
    
    
</div>

<?php include 'footer.php'; ?>