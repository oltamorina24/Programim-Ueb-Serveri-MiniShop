<?php 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<nav>
    <a href="index.php">Home</a>
    <a href="products.php">Products</a>
    <a href="search.php">Search</a>

    <?php 
 
    if(isset($_SESSION['user'])): 
    ?>
        <a href="logout.php" style="color: #ff4d4d; font-weight: bold;">Logout (<?php echo $_SESSION['user']['name']; ?>)</a>
    <?php else: ?>
        <a href="login.php" style="color: #2ecc71; font-weight: bold;">Login</a>
    <?php endif; ?>
</nav>
