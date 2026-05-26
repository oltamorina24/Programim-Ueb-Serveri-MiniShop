<?php 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<nav>
    <a href="index.php">Home</a>
    <a href="products.php">Products</a>

    <?php if(isset($_SESSION['user'])): ?>

        <?php if(!isAdmin()): ?>
            <a href="cart.php">Cart</a>
            <a href="orders.php">My Orders</a>
        <?php endif; ?>

        <?php if(isAdmin()): ?>
            <a href="admin.php" style="color: #f1c40f; font-weight: bold;">Admin Panel</a>
        <?php endif; ?>

        <a href="logout.php" style="color: #ff4d4d; font-weight: bold;">
            Logout (<?php echo $_SESSION['user']['name']; ?>)
        </a>

    <?php else: ?>

        <a href="login.php" style="color: #2ecc71; font-weight: bold;">Login</a>
        <a href="register.php" style="color:#3498db; font-weight:bold;">Register</a>

    <?php endif; ?>
</nav>