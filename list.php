<?php
session_start();
require_once 'db_config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$success_message = $error_message = '';

// Handle to-do item deletion
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_task'])) {
    $task_id = (int)$_POST['task_id'];
    
    try {
        $pdo->beginTransaction();
        
        // Delete task
        $stmt = $pdo->prepare("DELETE FROM to_do_lists WHERE id = ? AND user_id = ?");
        $stmt->execute([$task_id, $user_id]);
        
        $pdo->commit();
        $success_message = "Task deleted successfully!";
    } catch (PDOException $e) {
        $pdo->rollBack();
        $error_message = "Error deleting task: " . $e->getMessage();
    }
}

// Handle marking task as complete
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['complete_task'])) {
    $task_id = (int)$_POST['task_id'];
    
    try {
        $pdo->beginTransaction();
        
        // Update task status to 'complete'
        $stmt = $pdo->prepare("UPDATE to_do_lists SET status = 'complete' WHERE id = ? AND user_id = ?");
        $stmt->execute([$task_id, $user_id]);
        
        $pdo->commit();
        $success_message = "Task marked as complete!";
    } catch (PDOException $e) {
        $pdo->rollBack();
        $error_message = "Error marking task as complete: " . $e->getMessage();
    }
}

// Handle marking task as incomplete
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['incomplete_task'])) {
    $task_id = (int)$_POST['task_id'];
    
    try {
        $pdo->beginTransaction();
        
        // Update task status to 'incomplete'
        $stmt = $pdo->prepare("UPDATE to_do_lists SET status = 'incomplete' WHERE id = ? AND user_id = ?");
        $stmt->execute([$task_id, $user_id]);
        
        $pdo->commit();
        $success_message = "Task marked as incomplete!";
    } catch (PDOException $e) {
        $pdo->rollBack();
        $error_message = "Error marking task as incomplete: " . $e->getMessage();
    }
}

// Handle task editing
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['edit_task'])) {
    $task_id = (int)$_POST['task_id'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    
    try {
        $pdo->beginTransaction();
        
        // Update task details
        $stmt = $pdo->prepare("UPDATE to_do_lists SET title = ?, description = ? WHERE id = ? AND user_id = ?");
        $stmt->execute([$title, $description, $task_id, $user_id]);
        
        $pdo->commit();
        $success_message = "Task updated successfully!";
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
    
    <div class="container">
        <h2>My To-Do Lists</h2>
        
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
                <div class="task">
                    <h4><?php echo htmlspecialchars($task['title'], ENT_QUOTES, 'UTF-8'); ?></h4>
                    <p>Description: <?php echo htmlspecialchars($task['description'], ENT_QUOTES, 'UTF-8'); ?></p>
                    <p>Status: <?php echo htmlspecialchars($task['status'], ENT_QUOTES, 'UTF-8'); ?></p>
                    
                    <form method="post" action="">
                        <input type="hidden" name="task_id" value="<?php echo $task['id']; ?>">
                        <?php if ($task['status'] == 'incomplete'): ?>
                            <button type="submit" name="complete_task">Mark as Complete</button>
                        <?php else: ?>
                            <button type="submit" name="incomplete_task">Unmark Completion</button>
                        <?php endif; ?>
                        <button type="button" onclick="showEditForm(<?php echo $task['id']; ?>)">Edit</button>
                        <button type="submit" name="delete_task" onclick="return confirm('Are you sure you want to delete this task?')">Delete Task</button>
                    </form>

                    <div id="edit-form-<?php echo $task['id']; ?>" style="display: none;">
                        <form method="post" action="">
                            <input type="hidden" name="task_id" value="<?php echo $task['id']; ?>">
                            <input type="text" name="title" value="<?php echo htmlspecialchars($task['title'], ENT_QUOTES, 'UTF-8'); ?>" required>
                            <textarea name="description" required><?php echo htmlspecialchars($task['description'], ENT_QUOTES, 'UTF-8'); ?></textarea>
                            <button type="submit" name="edit_task">Update Task</button>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <script>
        function showEditForm(taskId) {
            var form = document.getElementById('edit-form-' + taskId);
            form.style.display = form.style.display === 'none' ? 'block' : 'none';
        }
    </script>
</body>
</html>
