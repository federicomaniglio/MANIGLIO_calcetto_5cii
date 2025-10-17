<nav class="navbar">
    <ul class = "nav-list">
        <li class = "nav-item">
            <a href="index.php" class = "nav-link">Home</a>
        </li>
        <?php if(isset($_SESSION['username'])): ?>
        <li class = "nav-item">
            <a href="profilo.php" class = "nav-link">Profilo</a>
        </li>
        <?php else : ?>
        <li class = "nav-item">
            <a href="login.php" class = "nav-link">Login</a>
        </li>
        <?php endif;?>

    </ul>
</nav>