<?php
$current_page = basename($_SERVER['PHP_SELF']);
?>
<link rel="stylesheet" href="styles.css">
<nav class="navbar">
    <div class="navbar-links">
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