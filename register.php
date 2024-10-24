<?php
require_once 'db_config.php';

$error = '';
$success = '';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['register'])) { 
    $username = htmlspecialchars($_POST['username'], ENT_QUOTES, 'UTF-8'); 
    $email = htmlspecialchars($_POST['email'], ENT_QUOTES, 'UTF-8'); 
    $password = $_POST['password']; // Jangan gunakan htmlspecialchars pada password 
    $confirm_password = $_POST['confirm_password']; 
    $recovery_question_1 = htmlspecialchars($_POST['recovery_question_1'], ENT_QUOTES, 'UTF-8');
    $recovery_answer_1 = htmlspecialchars($_POST['recovery_answer_1'], ENT_QUOTES, 'UTF-8');
    $recovery_question_2 = htmlspecialchars($_POST['recovery_question_2'], ENT_QUOTES, 'UTF-8');
    $recovery_answer_2 = htmlspecialchars($_POST['recovery_answer_2'], ENT_QUOTES, 'UTF-8');

    if ($password !== $confirm_password) { 
        $error = "Passwords do not match"; 
    } elseif (empty($recovery_question_1) || empty($recovery_answer_1) || empty($recovery_question_2) || empty($recovery_answer_2)) {
        $error = "Please select two recovery questions and provide answers.";
    } elseif ($recovery_question_1 == $recovery_question_2) {
        $error = "Recovery questions must be different.";
    } else {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $hashed_recovery_answer_1 = password_hash($recovery_answer_1, PASSWORD_DEFAULT);
        $hashed_recovery_answer_2 = password_hash($recovery_answer_2, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO users (username, email, password, recovery_question, recovery_answer, recovery_question_2, recovery_answer_2) VALUES (?, ?, ?, ?, ?, ?, ?)"); 
        if ($stmt->execute([$username, $email, $hashed_password, $recovery_question_1, $hashed_recovery_answer_1, $recovery_question_2, $hashed_recovery_answer_2])) { 
            $success = "Registration successful. Please log in."; 
        } else { 
            $error = "Registration failed. Please try again."; 
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Todo List Maker 2024</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>Register</h1>
        <?php
        if (!empty($error)) {
            echo "<p class='error'>" . htmlspecialchars($error, ENT_QUOTES, 'UTF-8') . "</p>";
        }
        if (!empty($success)) {
            echo "<p class='success'>" . htmlspecialchars($success, ENT_QUOTES, 'UTF-8') . "</p>";
        }
        ?>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"], ENT_QUOTES, 'UTF-8'); ?>">
            <input type="text" name="username" placeholder="Username" required> 
            <input type="email" name="email" placeholder="Email" required> 
            <input type="password" name="password" placeholder="Password" required> 
            <input type="password" name="confirm_password" placeholder="Confirm Password" required> 

    <!-- Pertanyaan Pemulihan -->
            <label for="recovery_question_1">Pilih Pertanyaan Pemulihan 1:</label>
        <select name="recovery_question_1" id="recovery_question_1" required>
            <option value="">-- Pilih Pertanyaan --</option>
            <option value="Siapa nama gadis ibu Anda?">Siapa nama gadis ibu Anda?</option>
            <option value="Apa nama hewan peliharaan pertama Anda?">Apa nama hewan peliharaan pertama Anda?</option>
            <option value="Apa nama sekolah dasar Anda?">Apa nama sekolah dasar Anda?</option>
        </select>
            <input type="text" name="recovery_answer_1" placeholder="Jawaban Anda" required>

        <label for="recovery_question_2">Pilih Pertanyaan Pemulihan 2:</label>
        <select name="recovery_question_2" id="recovery_question_2" required>
            <option value="">-- Pilih Pertanyaan --</option>
            <option value="Apa warna favorit Anda?">Apa warna favorit Anda?</option>
            <option value="Apa makanan favorit Anda?">Apa makanan favorit Anda?</option>
            <option value="Di kota mana Anda lahir?">Di kota mana Anda lahir?</option>
        </select>
            <input type="text" name="recovery_answer_2" placeholder="Jawaban Anda" required>        
    <button type="submit" name="register">Register</button>
    <a href="index.php" class="back-btn">Kembali ke Home</a>
    </div>
</body>
</html>