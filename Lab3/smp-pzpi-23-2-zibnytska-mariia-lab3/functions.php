<?php

function getDBConnection() {
    try {
        $pdo = new PDO('sqlite:shop_vesna.db');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch(PDOException $e) {
        die("Помилка підключення до БД: " . $e->getMessage());
    }
}

function getAllProducts() {
    $pdo = getDBConnection();
    $stmt = $pdo->query("SELECT * FROM products ORDER BY id");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getProductById($id) {
    $pdo = getDBConnection();
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function addToCart($product_id, $quantity) {
    $pdo = getDBConnection();
    
    $stmt = $pdo->prepare("SELECT * FROM cart WHERE product_id = ?");
    $stmt->execute([$product_id]);
    $existing = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($existing) {
        if ($quantity == 0) {
            $stmt = $pdo->prepare("DELETE FROM cart WHERE product_id = ?");
            $stmt->execute([$product_id]);
        } else {
            $stmt = $pdo->prepare("UPDATE cart SET quantity = ? WHERE product_id = ?");
            $stmt->execute([$quantity, $product_id]);
        }
    } else {
        if ($quantity > 0) {
            $stmt = $pdo->prepare("INSERT INTO cart (product_id, quantity) VALUES (?, ?)");
            $stmt->execute([$product_id, $quantity]);
        }
    }
}

function getCartItems() {
    $pdo = getDBConnection();
    $stmt = $pdo->query("
        SELECT c.*, p.name, p.price, p.image, (c.quantity * p.price) as total
        FROM cart c 
        JOIN products p ON c.product_id = p.id
        ORDER BY c.id
    ");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function removeFromCart($product_id) {
    $pdo = getDBConnection();
    $stmt = $pdo->prepare("DELETE FROM cart WHERE product_id = ?");
    $stmt->execute([$product_id]);
}

function clearCart() {
    $pdo = getDBConnection();
    $pdo->exec("DELETE FROM cart");
}

function getCartTotal() {
    $pdo = getDBConnection();
    $stmt = $pdo->query("
        SELECT SUM(c.quantity * p.price) as total
        FROM cart c 
        JOIN products p ON c.product_id = p.id
    ");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result['total'] ? $result['total'] : 0;
}

function getProductQuantityInCart($product_id) {
    $pdo = getDBConnection();
    $stmt = $pdo->prepare("SELECT quantity FROM cart WHERE product_id = ?");
    $stmt->execute([$product_id]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result ? $result['quantity'] : 0;
}
?>
