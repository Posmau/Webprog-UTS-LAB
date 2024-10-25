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

// Handle basic profile update
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

// Handle recovery questions update
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_recovery'])) {
    $recovery_question_1 = htmlspecialchars($_POST['recovery_question_1'], ENT_QUOTES, 'UTF-8');
    $recovery_answer_1 = htmlspecialchars($_POST['recovery_answer_1'], ENT_QUOTES, 'UTF-8');
    $recovery_question_2 = htmlspecialchars($_POST['recovery_question_2'], ENT_QUOTES, 'UTF-8');
    $recovery_answer_2 = htmlspecialchars($_POST['recovery_answer_2'], ENT_QUOTES, 'UTF-8');

    if (empty($recovery_question_1) || empty($recovery_answer_1) || empty($recovery_question_2) || empty($recovery_answer_2)) {
        $error_message = "Please select two recovery questions and provide answers.";
    } elseif ($recovery_question_1 == $recovery_question_2) {
        $error_message = "Recovery questions must be different.";
    } else {
        try {
            $hashed_recovery_answer_1 = password_hash($recovery_answer_1, PASSWORD_DEFAULT);
            $hashed_recovery_answer_2 = password_hash($recovery_answer_2, PASSWORD_DEFAULT);

            $stmt = $pdo->prepare("UPDATE users SET recovery_question = ?, recovery_answer = ?, recovery_question_2 = ?, recovery_answer_2 = ? WHERE id = ?");
            $stmt->execute([$recovery_question_1, $hashed_recovery_answer_1, $recovery_question_2, $hashed_recovery_answer_2, $user_id]);
            $success_message = "Recovery questions updated successfully!";

            // Update local user data
            $user['recovery_question'] = $recovery_question_1;
            $user['recovery_question_2'] = $recovery_question_2;
        } catch (PDOException $e) {
            $error_message = "Error updating recovery questions: " . $e->getMessage();
        }
    }
}

// Handle password update
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_password'])) {
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    
    if (password_verify($current_password, $user['password'])) {
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
    
    <div class="header">
        <div class="container">
            <h2>My Profile</h2>
            
            <?php if ($success_message): ?>
                <p class="success"><?php echo $success_message; ?></p>
            <?php endif; ?>
            <?php if ($error_message): ?>
                <p class="error"><?php echo $error_message; ?></p>
            <?php endif; ?>

            <?php if (isset($_GET['edit'])): ?> 
                <!-- Basic Profile Update Form -->
                <h3>Update Profile Information</h3>
                <form method="post" action="profile.php?edit=true"> 
                    <input type="text" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" placeholder="Username" required> 
                    <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" placeholder="Email" required> 
                    <button type="submit" name="update_profile">Update Profile</button>
                </form>

                <!-- Separate Recovery Questions Form -->
                <h3>Update Recovery Questions</h3>
                <form method="post" action="profile.php?edit=true">
                    <label for="recovery_question_1">Pilih Pertanyaan Pemulihan 1:</label>
                    <select name="recovery_question_1" id="recovery_question_1" required>
                        <option value="">-- Pilih Pertanyaan --</option>
                        <option value="Siapa nama gadis ibu Anda?" <?php if ($user['recovery_question'] == "Siapa nama gadis ibu Anda?") echo 'selected'; ?>>Siapa nama gadis ibu Anda?</option>
                        <option value="Apa nama hewan peliharaan pertama Anda?" <?php if ($user['recovery_question'] == "Apa nama hewan peliharaan pertama Anda?") echo 'selected'; ?>>Apa nama hewan peliharaan pertama Anda?</option>
                        <option value="Apa nama sekolah dasar Anda?" <?php if ($user['recovery_question'] == "Apa nama sekolah dasar Anda?") echo 'selected'; ?>>Apa nama sekolah dasar Anda?</option>
                    </select>
                    <input type="text" name="recovery_answer_1" placeholder="Jawaban Anda" required>

                    <label for="recovery_question_2">Pilih Pertanyaan Pemulihan 2:</label>
                    <select name="recovery_question_2" id="recovery_question_2" required>
                        <option value="">-- Pilih Pertanyaan --</option>
                        <option value="Apa warna favorit Anda?" <?php if ($user['recovery_question_2'] == "Apa warna favorit Anda?") echo 'selected'; ?>>Apa warna favorit Anda?</option>
                        <option value="Apa makanan favorit Anda?" <?php if ($user['recovery_question_2'] == "Apa makanan favorit Anda?") echo 'selected'; ?>>Apa makanan favorit Anda?</option>
                        <option value="Di kota mana Anda lahir?" <?php if ($user['recovery_question_2'] == "Di kota mana Anda lahir?") echo 'selected'; ?>>Di kota mana Anda lahir?</option>
                    </select>
                    <input type="text" name="recovery_answer_2" placeholder="Jawaban Anda" required>
                    <button type="submit" name="update_recovery">Update Recovery Questions</button>
                </form>

                <!-- Separate Password Update Form -->
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
            <a href="dashboard.php" class="back-btn">Kembali ke Main Page</a>
        </div>
    </div>

    <script>
        const toggleDarkModeBtn = document.getElementById('toggleDarkMode');
        toggleDarkModeBtn.addEventListener('click', function() {
            document.body.classList.toggle('dark-mode');
            
            if (document.body.classList.contains('dark-mode')) {
                localStorage.setItem('darkMode', 'enabled');
                toggleDarkModeBtn.textContent = 'Light Mode';
            } else {
                localStorage.setItem('darkMode', 'disabled');
                toggleDarkModeBtn.textContent = 'Dark Mode';
            }
        });
    </script>
</body>
</html>