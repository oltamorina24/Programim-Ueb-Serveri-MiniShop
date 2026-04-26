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
                    $img = !empty($p['image']) ? $p['image'] : "https://via.placeholder.com/200";
                    ?>
                    
                    <div class="product-card" style="background: white; width: 250px; border-radius: 12px; overflow: hidden; box-shadow: 0 10px 20px rgba(0,0,0,0.3); transition: transform 0.3s;">
                        <div style="width: 100%; height: 200px; overflow: hidden; background: #f9f9f9;">
                            <img src="<?php echo $img; ?>" 
                                 style="width: 100%; height: 100%; object-fit: contain; padding: 10px;" 
                                 alt="<?php echo htmlspecialchars($p['name']); ?>">
                        </div>
                        
                        <div style="padding: 15px; text-align: left;">
                            <span style="font-size: 12px; text-transform: uppercase; color: #95a5a6; font-weight: bold;">
                                <?php echo htmlspecialchars($p['category']); ?>
                            </span>
                            <h3 style="margin: 5px 0; color: #2c3e50; font-size: 1.2rem;">
                                <?php echo htmlspecialchars($p['name']); ?>
                            </h3>
                            <p style="font-size: 1.4rem; color: #e67e22; font-weight: bold; margin: 10px 0;">
                                <?php echo number_format($p['price'], 2); ?> €
                            </p>
                            
                            <a href="products.php" style="text-decoration: none;">
                                <button type="button" style="background: #3498db; color: white; border: none; width: 100%; padding: 10px; border-radius: 6px; cursor: pointer; font-weight: bold;">
                                    Shiko Detajet
                                </button>
                            </a>
                        </div>
                    </div>

                    <?php
                }
            }

            if(!$foundAny){
                echo "<div style='color:white; background: rgba(231, 76, 60, 0.2); padding: 20px; border-radius: 10px; width: 100%; max-width: 500px;'>";
                echo "Nuk u gjet asnjë produkt për: <strong>" . htmlspecialchars($input) . "</strong>";
                echo "</div>";
            }
        } else {
            echo "<p style='color: rgba(255,255,255,0.5); font-style: italic;'>Shkruani diçka për të filluar kërkimin...</p>";
        }
        ?>
    </div>
</div>

<?php
include "includes/footer.php";
?>