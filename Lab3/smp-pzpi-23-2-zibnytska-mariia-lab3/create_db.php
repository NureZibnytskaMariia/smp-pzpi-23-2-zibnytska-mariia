<?php

try {
    $pdo = new PDO('sqlite:shop_vesna.db');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $sql = "CREATE TABLE IF NOT EXISTS products (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        name TEXT NOT NULL,
        price REAL NOT NULL,
        image TEXT NOT NULL
    )";
    $pdo->exec($sql);
    
    $sql = "CREATE TABLE IF NOT EXISTS cart (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        product_id INTEGER NOT NULL,
        quantity INTEGER NOT NULL,
        FOREIGN KEY (product_id) REFERENCES products (id)
    )";
    $pdo->exec($sql);
    
    $stmt = $pdo->query("SELECT COUNT(*) FROM products");
    $count = $stmt->fetchColumn();
    
    if ($count == 0) {
        $products = [
            ['Молоко пастеризоване', 12.00, 'milk.jpg'],
            ['Хліб чорний', 9.00, 'black_bread.jpg'],
            ['Сир білий', 21.00, 'white_cheese.png'],
            ['Сметана 20%', 25.00, 'soure_cream.png'],
            ['Кефір 1%', 19.00, 'kefir.png'],
            ['Вода газована', 18.00, 'sparkling water.jpg'],
            ['Печиво "Весна"', 14.00, 'cookies.jpeg']
        ];
        
        $stmt = $pdo->prepare("INSERT INTO products (name, price, image) VALUES (?, ?, ?)");
        
        foreach ($products as $product) {
            $stmt->execute($product);
        }
        
        echo "База даних створена та заповнена початковими товарами!<br>";
    } else {
        echo "База даних вже існує!<br>";
    }
    
    echo "<a href='index.php'>Перейти до магазину</a>";
    
} catch(PDOException $e) {
    echo "Помилка створення бази даних: " . $e->getMessage();
}
?>