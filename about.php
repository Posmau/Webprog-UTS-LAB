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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <?php include 'navbar.php'; ?>
    <!-- Header dengan Efek Parallax -->
    <div class="header">
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
    <div class="about-section">
    <h3>Our Team</h3>
    <div class="team-container">
        <div class="team-card">
            <div class="team-image">
                <img src= "img/1.jpg" alt="Alif Faiz">
            </div>
            <h4>Alif Nurfaiz Widyatmoko</h4>
        </div>

        <div class="team-card">
            <div class="team-image">
                <img src="img/2.png" alt="Max">
            </div>
            <h4>Maxell Nathanael</h4>
        </div>

        <div class="team-card">
            <div class="team-image">
                <img src="img/3.png" alt="Alfin">
            </div>
            <h4>Alfin Sanders</h4>
        </div>

        <div class="team-card">
            <div class="team-image">
                <img src="img/4.png" alt="Kevan">
            </div>
            <h4>Eugenius Kevan Kusuma</h4>
            </div>
        </div>
    </div>
        <a href="dashboard.php" class="back-btn">Kembali ke Main Page</a>
        <!-- Tombol Scroll ke Atas -->
        <button id="scrollTopBtn" title="Kembali ke atas">
            <i class="fa-solid fa-arrow-up" style="color: #74C0FC;"></i>
        </button>
    </div>
</div>

        <!-- Script JavaScript -->
    <script>
        
        // Fungsi untuk Tombol Scroll ke Atas
        window.onscroll = function() {scrollFunction()};

        function scrollFunction() {
            const scrollTopBtn = document.getElementById("scrollTopBtn");
            if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
                scrollTopBtn.style.display = "block";
            } else {
                scrollTopBtn.style.display = "none";
            }
        }

        document.getElementById('scrollTopBtn').addEventListener('click', function(){
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });

        // Cek preferensi mode saat halaman dimuat
        window.addEventListener('load', function() {
            if (localStorage.getItem('darkMode') === 'enabled') {
                document.body.classList.add('dark-mode');
                toggleDarkModeBtn.textContent = 'Light Mode';
            }
        });

        function showEditProfile() {
            var form = document.getElementById('edit-profile-form');
            form.style.display = form.style.display === 'none' ? 'block' : 'none';
        }
    </script>
</script>
</body>
</html>
