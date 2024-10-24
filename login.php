<?php
session_start();
require_once 'db_config.php';

$error = '';
$success = '';

// Logout handler
if (isset($_GET['action']) && $_GET['action'] == 'logout') {
    // Clear all session variables
    $_SESSION = array();

    // Destroy the session cookie
    if (isset($_COOKIE[session_name()])) {
        setcookie(session_name(), '', time()-3600, '/');
    }

    // Destroy the session
    session_destroy();

    // Redirect to home page
    header("Location: index.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])) {
    $email = htmlspecialchars($_POST['email'], ENT_QUOTES, 'UTF-8');
    $password = $_POST['password']; // Do not use htmlspecialchars on passwords

    $stmt = $pdo->prepare("SELECT id, email, password, lockout_time, failed_login_attempts FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user) {
        // Cek lockout
        if ($user['lockout_time'] && strtotime($user['lockout_time']) > time()) {
            $error = "Akun Anda terkunci. Silakan gunakan fitur Lupa Password.";
        } else {
            if (password_verify($password, $user['password'])) {
                // Login berhasil
                $_SESSION['user_id'] = $user['id'];

                // Reset percobaan login gagal
                $stmt = $pdo->prepare("UPDATE users SET failed_login_attempts = 0, lockout_time = NULL WHERE id = ?");
                $stmt->execute([$user['id']]);

                header("Location: dashboard.php");
                exit();
            } else {
                // Password salah
                $failed_attempts = $user['failed_login_attempts'] + 1;
                $lockout_time = null;

                if ($failed_attempts >= 5) {
                    // Kunci akun
                    $lockout_time = date("Y-m-d H:i:s", strtotime('+1 minutes'));
                    $error = "Akun Anda terkunci setelah 5 kali percobaan login yang gagal.";

                    // Update percobaan login gagal dan waktu lockout
                    $stmt = $pdo->prepare("UPDATE users SET failed_login_attempts = ?, lockout_time = ? WHERE id = ?");
                    $stmt->execute([$failed_attempts, $lockout_time, $user['id']]);
                } else {
                    $error = "Email atau password salah.";

                    // Update percobaan login gagal
                    $stmt = $pdo->prepare("UPDATE users SET failed_login_attempts = ? WHERE id = ?");
                    $stmt->execute([$failed_attempts, $user['id']]);
                }
            }
        }
    } else {
        $error = "Email atau password salah.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - Todo List Maker 2024</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container"> 
        <h1>Login</h1> 
        <?php 
        if (!empty($error)) { 
            echo "<p class='error'>" . htmlspecialchars($error, ENT_QUOTES, 'UTF-8') . "</p>"; 
        } 
        ?> 
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"], ENT_QUOTES, 'UTF-8'); ?>"> 
            <input type="email" name="email" placeholder="Email" required> 
            <input type="password" name="password" placeholder="Password" required> 
            <button type="submit" name="login">Login</button> 
        </form> 
        <p><a href="forgot_password.php" class="forgot-password-link">Forgot Password?</a></p>
        <a href="register.php" class="btn">Don't have an account? Register here</a>
        <div>
        <a href="index.php" class="back-btn">Kembali ke Home</a>
        </div>
    </div> 
</body>
</html>
