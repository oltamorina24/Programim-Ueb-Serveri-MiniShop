<?php
require "data/products.php";
include "includes/header.php";
include "includes/nav.php";
?>

<div class="container" style="text-align: center; min-height: 80vh; padding-bottom: 50px;">
    <div style="max-width: 500px; margin: 40px auto; background: rgba(255,255,255,0.1); padding: 30px; border-radius: 15px; backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.2);">
        <h2 style="color: white; margin-bottom: 20px; font-family: sans-serif;">Kërko Produktin</h2>
        <form method="GET">
            <input type="text" name="q" placeholder="Psh: Coca Cola, Fanta, Chips..." 
                   style="width: 100%; padding: 12px; border-radius: 8px; border: none; font-size: 16px; outline: none;"
                   value="<?php echo isset($_GET['q']) ? htmlspecialchars($_GET['q']) : ''; ?>">
            <button type="submit" style="margin-top: 15px; width: 100%; font-weight: bold; padding: 12px; background: #2ecc71; color: white; border: none; border-radius: 8px; cursor: pointer; transition: 0.3s;">KËRKO</button>
        </form>
    </div>

    <hr style="margin: 40px 0; border: 0; border-top: 1px solid rgba(255,255,255,0.1);">

    <div class="search-results" style="display: flex; flex-wrap: wrap; justify-content: center; gap: 20px; padding: 0 10px;">
        <?php
        if(isset($_GET['q']) && !empty(trim($_GET['q']))){
            $input = $_GET['q'];
            $searchTerms = explode(',', $input);
            $foundAny = false;

            foreach($products as $p){
                $match = false;
    foreach($searchTerms as $term){
                    $trimmedTerm = trim($term);
                    if(!empty($trimmedTerm) && stripos($p['name'], $trimmedTerm) !== false){
                        $match = true;
                        break; 
                    }
                }

                if($match){
                    $foundAny = true;
                    echo "<div class='card' style='margin: 10px;'>";
                    
                    $img = "https://via.placeholder.com/200x120?text=" . urlencode($p['name']);
                    if($p['name'] == 'Coca Cola') $img = "https://images.unsplash.com/photo-1622483767028-3f66f34a50f4";
                    if($p['name'] == 'Laptop') $img = "https://images.unsplash.com/photo-1517336714731-489689fd1ca8";

                    echo "<img src='$img' style='width:100%; border-radius:8px;'>";
                    echo "<h3>" . htmlspecialchars($p['name']) . "</h3>";
                    echo "<p>" . $p['price'] . " €</p>";
                    echo "<a href='products.php'><button type='button' style='background:#3498db; 
                    width: 100%;'>Shiko</button></a>";
                    echo "</div>";
                }
            }

            if(!$foundAny){
                echo "<p style='color:white; font-size: 18px; margin-top: 20px;'>
                Nuk u gjet asnjë produkt për: <strong>" . 
                htmlspecialchars($input) . "</strong></p>";
            }
        }
        ?>
    </div>
</div>

<?php
include "includes/footer.php";
?>
