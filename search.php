<?php
require_once "includes/init.php";

$q = trim($_GET['q'] ?? '');
$products = [];

if ($q !== ''){
    try{
        $terms = array_filter(array_map('trim',explode(',', $q)));
        $where = [];
        $params = [];
        foreach($terms as $term){
            $where[] = "p.name LIKE ?";
            $params[] = '%' . $term . '%';
        }
        $sql = "SELECT p.*, c.name AS category_name FROM products p INNER JOIN categories c ON p.category_id = c.id WHERE " . implode(' OR ', $where) . "ORDER BY p.price ASC";
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        $products = $stmt->fetchAll();
    }catch (PDOException $e){
        $products = [];
    }
}

include "includes/header.php";
include "includes/nav.php";
?>

<div class="container" style="text-align: center; min-height: 80vh; padding-bottom: 50px;">
    <div style="max-width: 500px; margin: 40px auto; background: rgba(255,255,255,0.1); padding: 30px; border-radius: 15px; backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.2);">
        <h2 style="color: white; margin-bottom: 20px; font-family: sans-serif;">Kërko Produktin</h2>
        <form method="GET">
            <input type="text" name="q" placeholder="Psh: Coca Cola, Fanta, Chips..." 
                   style="width: 100%; padding: 12px; border-radius: 8px; border: none; font-size: 16px; outline: none; box-sizing:border-box;"
                   value="<?php echo e($q); ?>">
            <button type="submit" style="background: #2ecc71; color: white; border: none; padding:8px 14px; border-radius: 6px; cursor: pointer; font-weight: bold; font-size: 14px; height: 20px;">Search</button>
        </form>
    </div>

    <hr style="margin: 40px 0; border: 0; border-top: 1px solid rgba(255,255,255,0.1);">

    <div class="search-results" style="display: flex; flex-wrap: wrap; justify-content: center; gap: 20px; padding: 0 10px;">
        <?php if($q !== ''): ?>
            <?php if(!empty($products)): ?>
                <?php foreach($products as $p): ?>
                    
                    <div class="product-card" style="background: white; width: 250px; border-radius: 12px; overflow: hidden; box-shadow: 0 10px 20px rgba(0,0,0,0.3); transition: transform 0.3s;">
                        <div style="width: 100%; height: 200px; overflow: hidden; background: #f9f9f9;">
                            <img src="<?php echo e($p['image'] ?: 'https://via.placeholder.com/200'); ?>" 
                                 style="width: 100%; height: 100%; object-fit: contain; padding: 10px; box-sizing: border-box;" 
                                 alt="<?php echo e($p['name']); ?>">
                        </div>
                        
                        <div style="padding: 15px; text-align: left;">
                            <span style="font-size: 12px; text-transform: uppercase; color: #95a5a6; font-weight: bold;">
                                <?php echo e($p['category_name']); ?>
                            </span>
                            <h3 style="margin: 5px 0; color: #2c3e50; font-size: 1.2rem;">
                                <?php echo e($p['name']); ?>
                            </h3>
                            <p style="font-size: 1.4rem; color: #e67e22; font-weight: bold; margin: 10px 0;">
                                <?php echo number_format((float)$p['price'], 2); ?> €
                            </p>
                            
                            <a href="products.php" style="text-decoration: none;">
                                <button type="button" style="background: #3498db; color: white; border: none; width: 100%; padding: 10px; border-radius: 6px; cursor: pointer; font-weight: bold;">
                                    Shiko Detajet
                                </button>
                            </a>
                        </div>
                    </div>

                <?php endforeach; ?>
            <?php else: ?>
                <div style="color:white; background: rgba(231, 76, 60, 0.2); padding: 20px; border-radius: 10px; width: 100%; max-width: 500px;">
                
                Nuk u gjet asnje produkt per: <strong><?php echo e($q); ?></strong>
                </div>
            <?php endif; ?>
        <?php else: ?>
            <p style="color:rgba(255,255,255,0.5); font-style:italic;">Shkruani dicka oer te filluar kerkimin...</p>
        <?php endif; ?>
    </div>
</div>
<?php include "includes/footer.php"; ?>