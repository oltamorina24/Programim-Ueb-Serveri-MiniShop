<?php




<div class="container" style="display: flex; flex-direction: column; align-items: center; gap: 30px; padding-top: 50px;">
    
    <?php if($message): ?>
        <div style="background: #2ecc71; color: white; padding: 10px; border-radius: 5px;"><?php echo $message; ?></div>
    <?php endif; ?>

    <div style="background: rgba(255,255,255,0.1); backdrop-filter: blur(10px); padding: 30px; border-radius: 15px; width: 400px; color: white; border: 1px solid rgba(255,255,255,0.2);">
    <h3 style="text-align: center; margin-top: 0;">➕ Shto Produkt të Ri</h3>

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