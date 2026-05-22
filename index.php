<?php
require_once "includes/init.php";

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

try {
    $numriProdukteve = (int)$pdo->query("SELECT COUNT(*) FROM products")->fetchColumn();
    $numriKategorive = (int)$pdo->query("SELECT COUNT(*) FROM categories")->fetchColumn();
} catch (PDOException $e) {
    error_log("Gabim në DB: " . $e->getMessage());
    $numriProdukteve = 0;
    $numriKategorive = 0;
}

$emriPerdoruesit = isset($_SESSION['username']) && $_SESSION['username'] !== "" 
    ? htmlspecialchars($_SESSION['username'], ENT_QUOTES, 'UTF-8') 
    : "Vizitor";

include "includes/header.php";
include "includes/nav.php";
?>

<div class="overlay" style="padding-top:50px; min-height:80vh;">
    <div style="text-align:center; color:white; max-width:800px; margin:0 auto; background:rgba(0,0,0,0.6); padding:40px; border-radius:20px; backdrop-filter:blur(10px); border:1px solid rgba(255,255,255,0.1);">
        <h1 style="font-size:2.5rem; margin-bottom:10px;">Mirësevini, <?php echo $emriPerdoruesit; ?>! </h1>

        <p style="font-size:1.2rem; opacity:0.9; margin-bottom:30px;">Mirë se vini në dyqanin tonë. Eksploroni produktet më të reja.</p>

        <div style="display:flex; justify-content:space-around; margin:40px 0; gap:20px; flex-wrap:wrap;">
            <div style="background:rgba(255,255,255,0.1); padding:20px; border-radius:15px; flex:1; min-width:180px;">
                <h2 style="margin:0; color:#2ecc71;"><?php echo $numriProdukteve; ?></h2>
                <p style="margin:5px 0 0 0; opacity:0.8; font-size:14px;">Produkte në Stok</p>
            </div>
            <div style="background:rgba(255,255,255,0.1); padding:20px; border-radius:15px; flex:1; min-width:180px;">
                <h2 style="margin:0; color:#3498db;"><?php echo $numriKategorive; ?></h2>
                <p style="margin:5px 0 0 0; opacity:0.8; font-size:14px;">Kategori</p>
            </div>
        </div>

        <a href="products.php" style="text-decoration:none;">
            <button style="padding:15px 40px; font-size:1.1rem; font-weight:bold; border-radius:30px; background:#2ecc71; color:white; border:none; cursor:pointer;">Shiko Produktet </button>
        </a>
    </div>
</div>

<?php include "includes/footer.php"; ?>