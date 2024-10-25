<?php
session_start();
require_once 'db_config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$success_message = $error_message = '';

// Handle adding new list
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_list'])) {
    $title = htmlspecialchars($_POST['title']);
    $description = htmlspecialchars($_POST['description'] ?? '');
    
    try {
        $pdo->beginTransaction();
        
        // Insert the new list
        $stmt = $pdo->prepare("INSERT INTO to_do_lists (user_id, title, description, status) VALUES (?, ?, ?, 'incomplete')");
        $stmt->execute([$user_id, $title, $description]);
        
        $pdo->commit();
        $success_message = "List added successfully!";
    } catch (PDOException $e) {
        $pdo->rollBack();
        $error_message = "Error adding list: " . $e->getMessage();
    }
}

// Handle task deletion
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_task'])) {
    $task_id = (int)$_POST['task_id'];
    
    try {
        $pdo->beginTransaction();
        
        // Verify ownership before deletion
        $stmt = $pdo->prepare("SELECT user_id FROM to_do_lists WHERE id = ?");
        $stmt->execute([$task_id]);
        $task = $stmt->fetch();
        
        if ($task && $task['user_id'] == $user_id) {
            $stmt = $pdo->prepare("DELETE FROM to_do_lists WHERE id = ?");
            $stmt->execute([$task_id]);
            
            $pdo->commit();
            $success_message = "Task deleted successfully!";
        } else {
            $error_message = "Unauthorized action";
        }
    } catch (PDOException $e) {
        $pdo->rollBack();
        $error_message = "Error deleting task: " . $e->getMessage();
    }
}

// Handle marking task as complete/incomplete
if ($_SERVER['REQUEST_METHOD'] == 'POST' && (isset($_POST['complete_task']) || isset($_POST['incomplete_task']))) {
    $task_id = (int)$_POST['task_id'];
    $new_status = isset($_POST['complete_task']) ? 'complete' : 'incomplete';
    
    try {
        $pdo->beginTransaction();
        
        // Verify ownership before updating
        $stmt = $pdo->prepare("SELECT user_id FROM to_do_lists WHERE id = ?");
        $stmt->execute([$task_id]);
        $task = $stmt->fetch();
        
        if ($task && $task['user_id'] == $user_id) {
            $stmt = $pdo->prepare("UPDATE to_do_lists SET status = ? WHERE id = ?");
            $stmt->execute([$new_status, $task_id]);
            
            $pdo->commit();
            $success_message = "Task marked as " . $new_status . "!";
        } else {
            $error_message = "Unauthorized action";
        }
    } catch (PDOException $e) {
        $pdo->rollBack();
        $error_message = "Error updating task status: " . $e->getMessage();
    }
}

// Handle task editing
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['edit_task'])) {
    $task_id = (int)$_POST['task_id'];
    $title = htmlspecialchars($_POST['title']);
    $description = htmlspecialchars($_POST['description']);
    
    try {
        $pdo->beginTransaction();
        
        // Verify ownership before updating
        $stmt = $pdo->prepare("SELECT user_id FROM to_do_lists WHERE id = ?");
        $stmt->execute([$task_id]);
        $task = $stmt->fetch();
        
        if ($task && $task['user_id'] == $user_id) {
            $stmt = $pdo->prepare("UPDATE to_do_lists SET title = ?, description = ? WHERE id = ?");
            $stmt->execute([$title, $description, $task_id]);
            
            $pdo->commit();
            $success_message = "Task updated successfully!";
        } else {
            $error_message = "Unauthorized action";
        }
    } catch (PDOException $e) {
        $pdo->rollBack();
        $error_message = "Error updating task: " . $e->getMessage();
    }
}

// Fetch user's to-do lists
$stmt = $pdo->prepare("
    SELECT * FROM to_do_lists
    WHERE user_id = ?
    ORDER BY id DESC
");
$stmt->execute([$user_id]);
$to_do_lists = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My To-Do Lists - Todo List Maker 2024</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <?php include 'navbar.php'; ?>

    <!-- Header dengan Efek Parallax -->
    <div class="header">
    <div class="container">
        <h2>My To-Do Lists</h2>
        
        <!-- Add New List Form -->
        <div class="add-list-form">
            <h3>Add New List</h3>
            <form method="POST">
                <input type="text" name="title" placeholder="List Title" required>
                <textarea name="description" placeholder="Description"></textarea>
                <button type="submit" name="add_list">Add List</button>
            </form>
        </div>
        
        <?php if ($success_message): ?>
            <p class="success"><?php echo $success_message; ?></p>
        <?php endif; ?>
        <?php if ($error_message): ?>
            <p class="error"><?php echo $error_message; ?></p>
        <?php endif; ?>

        <?php if (empty($to_do_lists)): ?>
            <p>You don't have any tasks yet.</p>
        <?php else: ?>
            <?php foreach ($to_do_lists as $task): ?>
                <div class="task <?php echo $task['status']; ?>">
                    <h4><?php echo htmlspecialchars($task['title'], ENT_QUOTES, 'UTF-8'); ?></h4>
                    <p>Description: <?php echo htmlspecialchars($task['description'], ENT_QUOTES, 'UTF-8'); ?></p>
                    <p>Status: <?php echo htmlspecialchars($task['status'], ENT_QUOTES, 'UTF-8'); ?></p>
                    
                    <form method="post">
                        <input type="hidden" name="task_id" value="<?php echo $task['id']; ?>">
                        <?php if ($task['status'] == 'incomplete'): ?>
                            <button type="submit" name="complete_task">Mark as Complete</button>
                        <?php else: ?>
                            <button type="submit" name="incomplete_task">Mark as Incomplete</button>
                        <?php endif; ?>
                        <button type="button" onclick="showEditForm(<?php echo $task['id']; ?>)">Edit</button>
                        <button type="submit" name="delete_task" onclick="return confirm('Are you sure you want to delete this task?')">Delete Task</button>
                    </form>

                    <div id="edit-form-<?php echo $task['id']; ?>" class="edit-form" style="display: none;">
                        <form method="post">
                            <input type="hidden" name="task_id" value="<?php echo $task['id']; ?>">
                            <input type="text" name="title" value="<?php echo htmlspecialchars($task['title'], ENT_QUOTES, 'UTF-8'); ?>" required>
                            <textarea name="description" required><?php echo htmlspecialchars($task['description'], ENT_QUOTES, 'UTF-8'); ?></textarea>
                            <button type="submit" name="edit_task">Update Task</button>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
        <a href="dashboard.php" class="back-btn">Kembali ke Main Page</a>
    </div>
    </div>

    <script>
        // Fungsi untuk Mode Gelap
        const toggleDarkModeBtn = document.getElementById('toggleDarkMode');
            toggleDarkModeBtn.addEventListener('click', function() {
                document.body.classList.toggle('dark-mode');

            // Simpan preferensi mode di localStorage
            if (document.body.classList.contains('dark-mode')) {
                localStorage.setItem('darkMode', 'enabled');
                toggleDarkModeBtn.textContent = 'Light Mode';
            } else {
                localStorage.setItem('darkMode', 'disabled');
                toggleDarkModeBtn.textContent = 'Dark Mode';
            }
        });

        function showEditForm(taskId) {
            var form = document.getElementById('edit-form-' + taskId);
            form.style.display = form.style.display === 'none' ? 'block' : 'none';
        }
    </script>
</body>
</html>