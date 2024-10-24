<?php
session_start();
require_once 'db_config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$success_message = $error_message = '';

// Fetch user profile
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

// Handle profile update
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_profile'])) {
    $username = htmlspecialchars($_POST['username'], ENT_QUOTES, 'UTF-8');
    $email = htmlspecialchars($_POST['email'], ENT_QUOTES, 'UTF-8');
    try {
        $stmt = $pdo->prepare("UPDATE users SET username = ?, email = ? WHERE id = ?");
        $stmt->execute([$username, $email, $user_id]);
        $success_message = "Profile updated successfully!";
        
        // Update local user data
        $user['username'] = $username;
        $user['email'] = $email;
    } catch (PDOException $e) {
        $error_message = "Error updating profile: " . $e->getMessage();
    }
}

// Handle password update
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_password'])) {
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    
    // Verify current password
    if (password_verify($current_password, $user['password'])) {
        // Check if new password is different from current
        if ($current_password === $new_password) {
            $error_message = "New password must be different from current password";
        } else {
            try {
                $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
                $stmt->execute([$hashed_password, $user_id]);
                $success_message = "Password updated successfully!";
            } catch (PDOException $e) {
                $error_message = "Error updating password: " . $e->getMessage();
            }
        }
    } else {
        $error_message = "Current password is incorrect";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile - Division Defence Expo 2024</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <?php include 'navbar.php'; ?>
    
    <div class="container">
        <h2>My Profile</h2>
        
        <?php if ($success_message): ?>
            <p class="success"><?php echo $success_message; ?></p>
        <?php endif; ?>
        <?php if ($error_message): ?>
            <p class="error"><?php echo $error_message; ?></p>
        <?php endif; ?>

        <?php if (isset($_GET['edit'])): ?>
            <form method="post" action="profile.php">
                <input type="text" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" placeholder="Username" required>
                <input type="text" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" placeholder="Email" required>
                <button type="submit" name="update_profile">Update Profile</button>
            </form>
            
            <h3>Change Password</h3>
            <form method="post" action="profile.php?edit=true">
                <input type="password" name="current_password" placeholder="Current Password" required>
                <input type="password" name="new_password" placeholder="New Password" required>
                <button type="submit" name="update_password">Update Password</button>
            </form>
        <?php else: ?>
            <div class="profile-info">
                <p><strong>Username:</strong> <?php echo htmlspecialchars($user['username']); ?></p>
                <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
                <a href="profile.php?edit=true" class="btn">Edit Profile</a>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>