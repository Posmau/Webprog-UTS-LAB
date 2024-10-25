<?php
$current_page = basename($_SERVER['PHP_SELF']);
?>
<link rel="stylesheet" href="styles.css">
<nav class="navbar">
    <button class="navbar-toggle" onclick="toggleNavbar()">☰</button>

    <div class="menu-navbar" onclick="toggleNavbar()"></div>

    <div class="navbar-links">
            <!-- Tombol untuk Mode Gelap -->
        <button id="toggleDarkMode">Dark Mode</button>
        <a href="dashboard.php" <?php echo $current_page == 'dashboard.php' ? 'class="active"' : ''; ?>>Home</a>
        <a href="list.php" <?php echo $current_page == 'list.php' ? 'class="active"' : ''; ?>>My List</a>
        <a href="about.php" <?php echo $current_page == 'about.php' ? 'class="active"' : ''; ?>>About Us</a>
        <div class="profile-dropdown">
            <a href="profile.php" <?php echo $current_page == 'profile.php' ? 'class="active"' : ''; ?>>My Profile</a>
            <div class="profile-dropdown-content">
                <a href="profile.php">View Profile</a>
                <a href="profile.php?edit=true">Edit Profile</a>
                <a href="login.php?action=logout">Logout</a>
                
            </div>
        </div>
    </div>
</nav>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const toggleDarkModeBtn = document.getElementById('toggleDarkMode');

        // Aktifkan dark mode jika sudah disimpan di localStorage
        if (localStorage.getItem('darkMode') === 'enabled') {
            document.body.classList.add('dark-mode');
            toggleDarkModeBtn.textContent = 'Light Mode';
        } else {
            toggleDarkModeBtn.textContent = 'Dark Mode';
        }

        // Fungsi untuk tombol toggle
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
    });

    // Smaller screen menu toggle
    function toggleNavbar() {
        document.querySelector('.navbar').classList.toggle('active');
    }
</script>
