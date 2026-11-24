<?php
$host = 'localhost';
$dbname = 'todolist'; 
$username_db = 'root';
$password_db = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username_db, $password_db);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Cek apakah user admin sudah ada, jika belum buat otomatis
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = 'admin'");
    $stmt->execute();
    if ($stmt->rowCount() == 0) {
        $default_pass = password_hash('255314159', PASSWORD_DEFAULT);
        $insert = $pdo->prepare("INSERT INTO users (username, password) VALUES ('admin', ?)");
        $insert->execute([$default_pass]);
    }
} catch (PDOException $e) {
    die("Koneksi database gagal: " . $e->getMessage());
}
?>