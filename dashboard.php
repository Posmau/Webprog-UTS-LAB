<?php
session_start();
require_once 'db_config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();

$stmt = $pdo->prepare("SELECT * FROM to_do_lists WHERE user_id = ?");
$stmt->execute([$_SESSION['user_id']]);
$lists = $stmt->fetchAll();
?>

<!DOCTYPE html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard - Division Defence Expo 2024</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <?php include 'navbar.php'; ?>
    <h1>Welcome, <?= htmlspecialchars($user['username']); ?>!</h1>

    <div class="container">
        <!-- List To-Do Lists -->
        <ul>
        <?php foreach ($lists as $list): ?>
            <li><?= htmlspecialchars($list['title']); ?> 
                <a href="delete_list.php?id=<?= $list['id']; ?>">Delete</a>
            </li>
        <?php endforeach; ?>
        </ul>

        <!-- Add New To-Do List Form -->
        <form method="POST" action="add_list.php">
            <input type="text" name="title" placeholder="New List Title" required>
            <button type="submit">Add List</button>
        </form>

        <a href="login.php?action=logout" class="btn">Logout</a>
    </div>
</body>