<?php
session_start();
require 'db_config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = htmlspecialchars($_POST['title']);
    
    // Insert the new list into the database
    $stmt = $pdo->prepare("INSERT INTO to_do_lists (user_id, title) VALUES (?, ?)");
    $stmt->execute([$_SESSION['user_id'], $title]);
    
    header("Location: dashboard.php");
}
?>
