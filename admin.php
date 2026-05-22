<?php
require_once "includes/init.php";
requireAdmin();

$message = "";
$error = "";
$editProduct = null;
$editCategory = null;

try {
    if (isset($_GET['edit_product'])) {
        $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
        $stmt->execute([(int)$_GET['edit_product']]);
        $editProduct = $stmt->fetch();
    }

    if (isset($_GET['edit_category'])) {
        $stmt = $pdo->prepare("SELECT * FROM categories WHERE id = ?");
        $stmt->execute([(int)$_GET['edit_category']]);
        $editCategory = $stmt->fetch();
    }

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        if (isset($_POST['save_product'])) {
            $name = trim($_POST['pname'] ?? '');
            $price = $_POST['pprice'] ?? '';
            $categoryId = $_POST['pcat'] ?? '';
            $productId = isset($_POST['product_id']) ? (int)$_POST['product_id'] : 0;
            $oldImage = trim($_POST['old_image'] ?? '');
            $imageUrl = trim($_POST['pimage_url'] ?? '');

            $errors = validateProductInput($name, $price, $categoryId);
            if (!empty($imageUrl) && !filter_var($imageUrl, FILTER_VALIDATE_URL)) {
                $errors[] = 'URL e fotos nuk është valide.';
            }

            if (!empty($errors)) {
                $error = implode('<br>', array_map('e', $errors));
            } else {
                try {
                    $image = !empty($imageUrl) ? $imageUrl : handleProductImageUpload('pimage_file', $oldImage);

                    if ($productId > 0) {
                        $stmt = $pdo->prepare("UPDATE products SET name = ?, price = ?, category_id = ?, image = ? WHERE id = ?");
                        $stmt->execute([$name, (float)$price, (int)$categoryId, $image, $productId]);
                        $message = "Produkti u përditësua me sukses!";
                    } else {
                        $stmt = $pdo->prepare("INSERT INTO products (name, price, category_id, image) VALUES (?, ?, ?, ?)");
                        $stmt->execute([$name, (float)$price, (int)$categoryId, $image]);
                        $message = "Produkti u shtua me sukses!";
                    }
                    $editProduct = null;
                } catch (Exception $ex) {
                    $error = e($ex->getMessage());
                }
            }
        }

        if (isset($_POST['delete_product'])) {
            $stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
            $stmt->execute([(int)$_POST['product_id']]);
            $message = "Produkti u fshi me sukses!";
        }

        if (isset($_POST['save_category'])) {
            $categoryName = trim($_POST['category_name'] ?? '');
            $slug = strtolower(trim($_POST['category_slug'] ?? ''));
            $categoryId = isset($_POST['category_id']) ? (int)$_POST['category_id'] : 0;

            if (mb_strlen($categoryName) < 2 || mb_strlen($categoryName) > 100) {
                $error = "Emri i kategorisë duhet të ketë 2-100 karaktere.";
            } elseif (!preg_match('/^[a-z0-9-]+$/', $slug)) {
                $error = "Slug lejon vetëm shkronja të vogla, numra dhe vijë-minus.";
            } else {
                if ($categoryId > 0) {
                    $stmt = $pdo->prepare("UPDATE categories SET name = ?, slug = ? WHERE id = ?");
                    $stmt->execute([$categoryName, $slug, $categoryId]);
                    $message = "Kategoria u përditësua me sukses!";
                } else {
                    $stmt = $pdo->prepare("INSERT INTO categories (name, slug) VALUES (?, ?)");
                    $stmt->execute([$categoryName, $slug]);
                    $message = "Kategoria u shtua me sukses!";
                }
                $editCategory = null;
            }
        }

        if (isset($_POST['delete_category'])) {
            $categoryId = (int)$_POST['category_id'];
            $countStmt = $pdo->prepare("SELECT COUNT(*) FROM products WHERE category_id = ?");
            $countStmt->execute([$categoryId]);
            if ((int)$countStmt->fetchColumn() > 0) {
                $error = "Kategoria nuk mund të fshihet sepse ka produkte brenda.";
            } else {
                $stmt = $pdo->prepare("DELETE FROM categories WHERE id = ?");
                $stmt->execute([$categoryId]);
                $message = "Kategoria u fshi me sukses!";
            }
        }
    }

    $categories = $pdo->query("SELECT * FROM categories ORDER BY id ASC")->fetchAll();
    $products = $pdo->query("SELECT p.*, c.name AS category_name FROM products p INNER JOIN categories c ON p.category_id = c.id ORDER BY p.id DESC")->fetchAll();
} catch (PDOException $e) {
    $error = "Gabim në databazë: " . e($e->getMessage());
    $categories = [];
    $products = [];
}

include "includes/header.php";
include "includes/nav.php";
?>

<div class="container" style="display:flex; flex-direction:column; align-items:center; gap:30px; padding-top:50px; color:white;">
    <?php if ($message): ?>
        <div style="background:#2ecc71; color:white; padding:10px 20px; border-radius:5px; text-align:center;"><?php echo e($message); ?></div>
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
                                
                                <form method="POST" onsubmit="return confirm('A je i sigurt që dëshiron të fshish këtë produkt?');" style="margin:0;">
                                    <input type="hidden" name="product_id" value="<?php echo (int)$p['id']; ?>">
                                    <button type="submit" name="delete_product" style="background:#ff4d4d; color:white; border:none; padding:8px 12px; border-radius:5px; cursor:pointer; font-weight:bold;">Delete</button>
                                </form>
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

<?php include "includes/footer.php"; ?>