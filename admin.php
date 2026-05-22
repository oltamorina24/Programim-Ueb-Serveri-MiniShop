<?php
session_start();
$filePath = 'data/products.php';
if (file_exists($filePath)){
    require $filePath;
}else{
    $products = [];
}
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin'){
    header("Location: index.php");
    exit();
}
$message = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['shto_produkt'])){
        $products[] = [
            'name' => $_POST['pname'],
            'price' => (float)$_POST['pprice'],
            'category' => $_POST['pcat'],
            'image' => ""
        ];
        $message = "Produkti u shtua me sukses!";
    }
if (isset($_POST['fshij_produkt'])) {
        $emriPerFshirje = $_POST['pname_delete'];
        $products = array_filter($products, function($p) use ($emriPerFshirje) {
            return $p['name'] !== $emriPerFshirje;
        });
        $products = array_values($products); 
        $message = "Produkti u fshi me sukses!";
    }
    $content = "<?php\n";
    $content .= "\$products = [\n";
    foreach ($products as $p) {
        $content .= "    [\"name\" => \"" . addslashes($p['name']) . "\", \"price\" => " . $p['price'] . ", \"category\" => \"" . $p['category'] . "\", \"image\" => \"" . $p['image'] . "\"],\n";
    }
    $content .= "];";
    file_put_contents($filePath, $content);
}
include "includes/header.php";
include "includes/nav.php";
?>

<div class="container" style="display: flex; flex-direction: column; align-items: center; gap: 30px; padding-top: 50px;">
    <?php if($message): ?>
        <div style="background: #2ecc71; color: white; padding: 10px; border-radius: 5px;"><?php echo $message; ?></div>
    <?php endif; ?>

    <div style="background: rgba(255,255,255,0.1); backdrop-filter: blur(10px); padding: 30px; border-radius: 15px; width: 400px; color: white; border: 1px solid rgba(255,255,255,0.2);">
    <h3 style="text-align: center; margin-top: 0;">➕ Shto Produkt të Ri</h3>

<<<<<<< Updated upstream
    <form method="POST" autocomplete="off">
        <input type="text" name="pname" placeholder="Emri i produktit" required style="width:100%; padding:10px; margin:10px 0; border-radius:5px; border:none;">
        <input type="number" step="0.01" name="pprice" placeholder="Çmimi (€)" required style="width:100%; padding:10px; margin:10px 0; border-radius:5px; border:none;">
        <select name="pcat" style="width: 100%; padding: 10px; margin: 10px 0; border-radius:5px;">
            <option value="drinks">Pije</option>
            <option value="sweet">Ëmbëlsira</option>
            <option value="salt">Të kripura</option>
        </select>
        <button type="submit" name="shto_produkt" style="width: 100%; background:#2ecc71; color:white; border:none; padding:12px; cursor:pointer; font-weight:bold; border-radius:5px;">SHTO</button>
    </form>
=======
    <div style="display:flex; gap:30px; flex-wrap:wrap; justify-content:center; width:100%;">
        <div style="background:rgba(255,255,255,0.1); backdrop-filter:blur(10px); padding:30px; border-radius:15px; width:400px; border:1px solid rgba(255,255,255,0.2);">
            <h3 style="text-align:center; margin-top:0;"> <?php echo $editProduct ? '📝 Ndrysho Produktin' : '➕ Shto Produkt të Ri'; ?></h3>

            <form method="POST" enctype="multipart/form-data" autocomplete="off">
                <input type="hidden" name="product_id" value="<?php echo e($editProduct['id'] ?? '0'); ?>">
                <input type="hidden" name="old_image" value="<?php echo e($editProduct['image'] ?? ''); ?>">

                <input type="text" name="pname" placeholder="Emri i produktit" required value="<?php echo e($editProduct['name'] ?? ''); ?>" style="width:100%; padding:10px; margin:10px 0; border-radius:5px; border:none; box-sizing:border-box;">
                <input type="number" step="0.01" name="pprice" placeholder="Çmimi (€)" required value="<?php echo e($editProduct['price'] ?? ''); ?>" style="width:100%; padding:10px; margin:10px 0; border-radius:5px; border:none; box-sizing:border-box;">

                <select name="pcat" required style="width:100%; padding:10px; margin:10px 0; border-radius:5px; box-sizing:border-box;">
                    <option value="" disabled <?php echo !isset($editProduct['category_id']) ? 'selected' : ''; ?>>Zgjidh Kategorinë</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?php echo (int)$cat['id']; ?>" <?php echo isset($editProduct['category_id']) && $editProduct['category_id'] == $cat['id'] ? 'selected' : ''; ?>>
                            <?php echo e($cat['name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <input type="url" name="pimage_url" placeholder="Link fotoje ose lëre bosh për upload" value="<?php echo e($editProduct['image'] ?? ''); ?>" style="width:100%; padding:10px; margin:10px 0; border-radius:5px; border:none; box-sizing:border-box;">
                <label style="font-size:13px; opacity:0.9;">Ose ngarko foto nga kompjuteri:</label>
                <input type="file" name="pimage_file" accept="image/*" style="width:100%; padding:10px; margin:10px 0; border-radius:5px; background:white; color:#333; box-sizing:border-box;">

                <button type="submit" name="save_product" style="width:100%; background:#2ecc71; color:white; border:none; padding:12px; cursor:pointer; font-weight:bold; border-radius:5px;">
                    <?php echo $editProduct ? 'RUAJ NDRYSHIMET' : 'SHTO'; ?>
                </button>
                <?php if ($editProduct): ?>
                    <a href="admin.php" style="display:block; color:white; text-align:center; margin-top:10px; text-decoration:none;">❌ Anulo</a>
                <?php endif; ?>
            </form>
        </div>

        <div style="background:rgba(255,255,255,0.1); backdrop-filter:blur(10px); padding:30px; border-radius:15px; width:400px; border:1px solid rgba(255,255,255,0.2);">
            <h3 style="text-align:center; margin-top:0;"> <?php echo $editCategory ? '📝 Ndrysho Kategorinë' : '📂 Shto Kategori'; ?></h3>
            <form method="POST" autocomplete="off">
                <input type="hidden" name="category_id" value="<?php echo e($editCategory['id'] ?? '0'); ?>">
                <input type="text" name="category_name" placeholder="Emri p.sh. Pijet (Drinks)" required value="<?php echo e($editCategory['name'] ?? ''); ?>" style="width:100%; padding:10px; margin:10px 0; border-radius:5px; border:none; box-sizing:border-box;">
                <input type="text" name="category_slug" placeholder="Slug p.sh. drinks" required value="<?php echo e($editCategory['slug'] ?? ''); ?>" style="width:100%; padding:10px; margin:10px 0; border-radius:5px; border:none; box-sizing:border-box;">
                <button type="submit" name="save_category" style="width:100%; background:#3498db; color:white; border:none; padding:12px; cursor:pointer; font-weight:bold; border-radius:5px;">
                    <?php echo $editCategory ? 'RUAJ KATEGORINË' : 'SHTO KATEGORI'; ?>
                </button>
                <?php if ($editCategory): ?>
                    <a href="admin.php" style="display:block; color:white; text-align:center; margin-top:10px; text-decoration:none;">❌ Anulo</a>
                <?php endif; ?>
            </form>
        </div>
    </div>

    <div style="width:95%; max-width:1000px; background:rgba(0,0,0,0.45); padding:20px; border-radius:15px;">
        <h3>📦 Produktet ekzistuese</h3>
        <div style="overflow-x:auto;">
            <table style="width:100%; border-collapse:collapse; color:white;">
                <tr style="background:rgba(255,255,255,0.15);">
                    <th style="padding:10px; text-align:left;">Foto</th>
                    <th style="padding:10px; text-align:left;">Emri</th>
                    <th style="padding:10px; text-align:left;">Çmimi</th>
                    <th style="padding:10px; text-align:left;">Kategoria</th>
                    <th style="padding:10px; text-align:left;">Veprime</th>
                </tr>
                <?php foreach ($products as $p): ?>
                    <tr style="border-bottom: 1px solid rgba(255,255,255,0.1);">
                        <td style="padding:10px;"><img src="<?php echo e($p['image']); ?>" style="width:45px; height:45px; object-fit:contain; background:white; border-radius:6px;"></td>
                        <td style="padding:10px;"><?php echo e($p['name']); ?></td>
                        <td style="padding:10px;"><?php echo number_format((float)$p['price'], 2); ?> €</td>
                        <td style="padding:10px;"><?php echo e($p['category_name']); ?></td>
                        <td style="padding:10px;">
                            <div style="display:flex; gap:8px; align-items:center;">
                                <a href="admin.php?edit_product=<?php echo (int)$p['id']; ?>" style="background:#f1c40f; color:#222; padding:8px 12px; border-radius:5px; text-decoration:none; font-weight:bold;">Edit</a>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        </div>
    </div>

    <div style="width:95%; max-width:1000px; background:rgba(0,0,0,0.45); padding:20px; border-radius:15px;">
        <h3>📂 Kategoritë ekzistuese</h3>
        <div style="display:flex; gap:10px; flex-wrap:wrap;">
            <?php foreach ($categories as $cat): ?>
                <div style="background:white; color:#2c3e50; padding:12px; border-radius:10px; min-width:180px;">
                    <strong><?php echo e($cat['name']); ?></strong><br>
                    <small style="color:#7f8c8d;"><?php echo e($cat['slug']); ?></small>
                    <div style="display:flex; gap:6px; margin-top:10px;">
                        <a href="admin.php?edit_category=<?php echo (int)$cat['id']; ?>" style="background:#f1c40f; color:#222; padding:6px 10px; border-radius:5px; text-decoration:none; font-size:13px; font-weight:bold;">Edit</a>
                        <form method="POST" onsubmit="return confirm('A je i sigurt që dëshiron të fshish këtë kategori?');" style="margin:0;">
                            <input type="hidden" name="category_id" value="<?php echo (int)$cat['id']; ?>">
                            <button type="submit" name="delete_category" style="background:#ff4d4d; color:white; border:none; padding:6px 10px; border-radius:5px; cursor:pointer; font-size:13px; font-weight:bold;">Delete</button>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<div style="background: rgba(255,255,255,0.1); backdrop-filter: blur(10px); padding: 30px; border-radius: 15px; width: 400px; color: white; border: 1px solid rgba(255,255,255,0.2);">
        <h3 style="text-align: center; margin-top: 0;">🗑️ Fshij një Produkt</h3>
        <form method="POST">
            <label>Zgjidh produktin që dëshiron të fshish:</label>
            <select name="pname_delete" style="width: 100%; padding: 10px; margin: 10px 0; border-radius:5px;">
                <?php foreach($products as $p): ?>
                    <option value="<?php echo $p['name']; ?>"><?php echo $p['name']; ?> (<?php echo $p['price']; ?>€)</option>
                <?php endforeach; ?>
            </select>
            <button type="submit" name="fshij_produkt" onclick="return confirm('A jeni i sigurt?')" style="width: 100%; background:#ff4d4d; color:white; border:none; padding:12px; cursor:pointer; font-weight:bold; border-radius:5px;">FSHIJ PRODUKTIN</button>
        </form>
    </div>

</div>

<?php include "includes/footer.php"; 
?>