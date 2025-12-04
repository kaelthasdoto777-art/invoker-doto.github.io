<?php
// config.php - подключение к базе данных InvokerDoto
$host = 'localhost';
$dbname = 'InvokerDoto';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    // Если база данных не существует, создаем ее
    if ($e->getCode() == 1049) {
        die("Database 'InvokerDoto' not found. Please run <a href='setup.php'>setup.php</a> first.");
    } else {
        die("Could not connect to the database: " . $e->getMessage());
    }
}
?>