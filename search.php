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
