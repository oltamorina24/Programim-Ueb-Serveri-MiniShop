<?php 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<nav>
    <a href="index.php">Home</a>
    <a href="products.php">Products</a>
    <a href="search.php">Search</a>

    <?php if(isset($_SESSION['user'])): ?>
        
        <?php if($_SESSION['user']['role'] === 'admin'): ?>
            <a href="admin.php" style="color: #f1c40f; font-weight: bold;">+ Shto Produkt</a>
        <?php endif; ?>

        <a href="logout.php" style="color: #ff4d4d; font-weight: bold;">Logout (<?php echo $_SESSION['user']['name']; ?>)</a>
    
    <?php else: ?>
        <a href="login.php" style="color: #2ecc71; font-weight: bold;">Login</a>
    <?php endif; ?>
</nav>