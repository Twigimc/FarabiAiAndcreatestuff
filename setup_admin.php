<?php
require_once 'db.php';

try {
    // Create admins table
    $sql = "CREATE TABLE IF NOT EXISTS admins (
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(100) UNIQUE NOT NULL,
        password_hash VARCHAR(255) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";
    $pdo->exec($sql);
    
    // Check if any admin exists
    $stmt = $pdo->query("SELECT COUNT(*) FROM admins");
    if ($stmt->fetchColumn() > 0) {
        echo "An admin already exists. To generate a new one, delete the existing record first.\n";
        exit;
    }

    // Generate random credentials
    $randomUsername = 'admin_' . substr(str_shuffle("abcdefghijklmnopqrstuvwxyz0123456789"), 0, 5);
    $randomPassword = substr(str_shuffle("abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*"), 0, 12);
    
    // Hash password
    $hashedPassword = password_hash($randomPassword, PASSWORD_DEFAULT);

    // Insert into DB
    $stmt = $pdo->prepare("INSERT INTO admins (username, password_hash) VALUES (?, ?)");
    $stmt->execute([$randomUsername, $hashedPassword]);

    echo "--- ADMIN CREDENTIALS CREATED ---\n";
    echo "Username: $randomUsername\n";
    echo "Password: $randomPassword\n";
    echo "---------------------------------\n";
    echo "PLEASE SAVE THESE CREDENTIALS SECURELY.\n";

} catch(PDOException $e) {
    die("Error setting up admins: " . $e->getMessage());
}
?>
