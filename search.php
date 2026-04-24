<?php
require "data/products.php";
include "includes/header.php";
include "includes/nav.php";
?>

<div class="container" style="text-align: center;">
    <div style="max-width: 450px; margin: 0 auto; background: rgba(255,255,255,0.1); padding: 25px; border-radius: 15px; backdrop-filter: blur(5px);">
        <h2 style="color: white; margin-bottom: 20px;">Kërko Produktin</h2>
        <form method="GET">
            <input type="text" name="q" placeholder="Psh: Coca Cola, Fanta, Chips..." 
                   style="width: 85%; padding: 12px; border-radius: 8px; border: none; font-size: 16px;"
                   value="<?php echo isset($_GET['q']) ? htmlspecialchars($_GET['q']) : ''; ?>">
            <button style="margin-top: 15px; width: 90%; font-weight: bold;">Kërko</button>
        </form>
    </div>

    <hr style="margin-top: 40px; border: 0.5px solid rgba(255,255,255,0.2);">

    <div class="search-results" style="display: flex; flex-wrap: wrap; justify-content: center; margin-top: 20px;">
        <?php
        if(isset($_GET['q']) && !empty(trim($_GET['q']))){
            $input = $_GET['q'];
            
            // 1. I ndajmë fjalët e kërkuara nëse përdoruesi ka vënë presje
            $searchTerms = explode(',', $input);
            $foundAny = false;

            foreach($products as $p){
                $match = false;
?>