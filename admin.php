<?php
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