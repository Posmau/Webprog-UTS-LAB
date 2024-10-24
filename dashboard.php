<?php
session_start();
require_once 'db_config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Get sort parameter from URL, default to 'all'
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'all';

$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();

// Modify query based on sort parameter
$query = "SELECT * FROM to_do_lists WHERE user_id = ?";
if ($sort === 'complete') {
    $query .= " AND status = 'complete'";
} elseif ($sort === 'incomplete') {
    $query .= " AND status = 'incomplete'";
}
$query .= " ORDER BY id DESC";

$stmt = $pdo->prepare($query);
$stmt->execute([$_SESSION['user_id']]);
$lists = $stmt->fetchAll();

// Get counts for different statuses
$stmt = $pdo->prepare("SELECT 
    COUNT(*) as total,
    SUM(CASE WHEN status = 'complete' THEN 1 ELSE 0 END) as completed,
    SUM(CASE WHEN status = 'incomplete' THEN 1 ELSE 0 END) as incomplete
    FROM to_do_lists 
    WHERE user_id = ?");
$stmt->execute([$_SESSION['user_id']]);
$stats = $stmt->fetch();
?>

<!DOCTYPE html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Todo List Maker</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <?php include 'navbar.php'; ?>
    
    <div class="container">
        <h1>Welcome, <?= htmlspecialchars($user['username']); ?>!</h1>

        <div class="dashboard-summary">
            <div class="stats">
                <div class="stat-card <?= $sort === 'all' ? 'active' : '' ?>">
                    <a href="?sort=all" class="stat-link">
                        <h3>Total Lists</h3>
                        <p><?= $stats['total'] ?></p>
                    </a>
                </div>
                <div class="stat-card <?= $sort === 'complete' ? 'active' : '' ?>">
                    <a href="?sort=complete" class="stat-link">
                        <h3>Completed</h3>
                        <p><?= $stats['completed'] ?></p>
                    </a>
                </div>
                <div class="stat-card <?= $sort === 'incomplete' ? 'active' : '' ?>">
                    <a href="?sort=incomplete" class="stat-link">
                        <h3>Incomplete</h3>
                        <p><?= $stats['incomplete'] ?></p>
                    </a>
                </div>
            </div>
        </div>

        <div class="todo-lists">
            <div class="list-header">
                <h2>Your Todo Lists</h2>
                <div class="sort-controls">
                    <label for="sort-select">Sort by:</label>
                    <select id="sort-select" onchange="window.location.href=this.value">
                        <option value="?sort=all" <?= $sort === 'all' ? 'selected' : '' ?>>All Lists</option>
                        <option value="?sort=complete" <?= $sort === 'complete' ? 'selected' : '' ?>>Completed</option>
                        <option value="?sort=incomplete" <?= $sort === 'incomplete' ? 'selected' : '' ?>>Incomplete</option>
                    </select>
                </div>
            </div>
            
            <?php if (empty($lists)): ?>
                <p class="no-lists">
                    <?php
                    if ($sort === 'complete') {
                        echo "No completed lists found.";
                    } elseif ($sort === 'incomplete') {
                        echo "No incomplete lists found.";
                    } else {
                        echo "You haven't created any lists yet.";
                    }
                    ?>
                </p>
            <?php else: ?>
                <div class="lists-container">
                    <?php foreach ($lists as $list): ?>
                        <div class="list-card <?= $list['status'] ?>">
                            <h3><?= htmlspecialchars($list['title']); ?></h3>
                            <?php if (!empty($list['description'])): ?>
                                <p class="description"><?= htmlspecialchars($list['description']); ?></p>
                            <?php endif; ?>
                            <div class="status-badge <?= $list['status'] ?>">
                                <?= ucfirst($list['status']) ?>
                            </div>
                            <div class="list-actions">
                                <a href="list.php?id=<?= $list['id']; ?>" class="btn btn-view">View List</a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <div class="add-list-section">
                <a href="list.php" class="btn btn-primary">Create New List</a>
            </div>
        </div>

        <div class="dashboard-footer">
            <a href="login.php?action=logout" class="btn btn-logout">Logout</a>
        </div>
    </div>
</body>
</html>