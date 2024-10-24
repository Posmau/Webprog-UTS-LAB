<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - Division Defence Expo 2024</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <?php include 'navbar.php'; ?>
    
    <div class="container">
        <h2>About US</h2>
        
        <div class="about-section">
            <h3>Who We Are</h3>
            <p>ToDoMaster adalah platform digital yang dirancang untuk membantu Anda mengelola tugas sehari-hari dengan mudah. Baik Anda mengatur pekerjaan kantor, urusan pribadi, atau sekedar mencatat daftar belanja, ToDoMaster menyediakan solusi sederhana dan efisien untuk menjaga produktivitas Anda.</p>
            
            <h3>Our Mission</h3>
            <p>Misi kami adalah memberikan kemudahan dalam mengatur waktu dan prioritas, membantu pengguna tetap fokus dan terorganisir. Kami percaya bahwa dengan manajemen tugas yang baik, setiap orang bisa mencapai lebih banyak, baik di dunia kerja maupun kehidupan pribadi.</p>
            
            <h3>What We Offer</h3>
            <ul>
                <li>Kategori To-Do yang Fleksibel: Buat daftar tugas untuk Work, Personal, atau Grocery, sesuai kebutuhan Anda.</li>
                <li>Pengingat yang Disesuaikan: Atur pengingat otomatis agar tidak melewatkan tenggat waktu penting.</li>
                <li>Integrasi Multi-Device: Akses daftar Anda dari mana saja, kapan saja, baik di desktop maupun perangkat mobile.</li>
                <li>Kolaborasi Tugas: Bagikan daftar tugas Anda dengan rekan kerja, keluarga, atau teman untuk bekerja sama secara lebih efisien.</li>
                <li>Analisis Produktivitas: Lacak perkembangan dan penyelesaian tugas untuk melihat seberapa produktif Anda.</li>
            </ul>
            
            <h3>Contact Information</h3>
            <p>Email: support@todomaster.com</p>
            <p>Phone: +1 (555) 987-6543</p>
            <p>Address: 456 Productivity Avenue, Task City, TC 67890</p>
        </div>
    </div>
</body>
</html>