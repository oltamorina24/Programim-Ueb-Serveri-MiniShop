<?php
require_once "includes/init.php";

$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'])) {
    if (!isLoggedIn()) {
        redirect('login.php');
    }

    $productId = filter_input(INPUT_POST, 'product_id', FILTER_VALIDATE_INT);

    if (!$productId) {
        $error = 'Produkti nuk është valid.';
    } else {
        $stmt = $pdo->prepare("SELECT id, name, price, image FROM products WHERE id = ? LIMIT 1");
        $stmt->execute([$productId]);
        $product = $stmt->fetch();

        if (!$product) {
            $error = 'Produkti nuk u gjet.';
        } else {
            if (!isset($_SESSION['cart'])) {
                $_SESSION['cart'] = [];
            }

            if (isset($_SESSION['cart'][$productId])) {
                $_SESSION['cart'][$productId]['quantity']++;
            } else {
                $_SESSION['cart'][$productId] = [
                    'id' => $product['id'],
                    'name' => $product['name'],
                    'price' => $product['price'],
                    'image' => $product['image'],
                    'quantity' => 1
                ];
            }

            redirect('cart.php');
        }
    }
}

try {
    if ($search !== '') {
        $stmt = $pdo->prepare("
            SELECT 
                p.id, 
                p.name, 
                p.price, 
                p.image, 
                c.name AS category_name, 
                c.slug AS category_slug
            FROM products p
            INNER JOIN categories c ON p.category_id = c.id
            WHERE p.name LIKE ?
            ORDER BY c.id ASC, p.price ASC
        ");
        $stmt->execute(["%" . $search . "%"]);
    } else {
        $stmt = $pdo->prepare("
            SELECT 
                p.id, 
                p.name, 
                p.price, 
                p.image, 
                c.name AS category_name, 
                c.slug AS category_slug
            FROM products p
            INNER JOIN categories c ON p.category_id = c.id
            ORDER BY c.id ASC, p.price ASC
        ");
        $stmt->execute();
    }

    $products = $stmt->fetchAll();

    $catStmt = $pdo->query("SELECT id, name, slug FROM categories ORDER BY id ASC");
    $categories = $catStmt->fetchAll();

} catch (PDOException $e) {
    $products = [];
    $categories = [];
}

include "includes/header.php";
include "includes/nav.php";
?>

<div class="container" style="padding-top:20px;">

    <?php if ($message): ?>
        <div style="max-width:760px; margin:20px auto; background:#2ecc71; color:white; padding:12px; border-radius:8px; text-align:center;">
            <?php echo e($message); ?>
        </div>
    <?php endif; ?>

    <?php if ($error): ?>
        <div style="max-width:760px; margin:20px auto; background:#ff4d4d; color:white; padding:12px; border-radius:8px; text-align:center;">
            <?php echo e($error); ?>
        </div>
    <?php endif; ?>

    <div style="display:flex; justify-content:center; margin:50px 0 25px 0;">
        <form method="GET" action="products.php" style="display:flex; gap:10px; align-items:center;">
            
            <input 
                type="text" 
                name="search" 
                value="<?php echo e($search); ?>" 
                placeholder="Kërko produkt..." 
                style="
                    width:320px;
                    height:40px;
                    padding:0 18px;
                    border-radius:6px;
                    border:none;
                    font-size:18px;
                    box-sizing:border-box;
                    line-height:60px;
                "
            >

            <button 
                type="submit" 
                style="
                    width:130px;
                    height:40px;
                    padding:0;
                    background:#2ecc71;
                    color:white;
                    border:none;
                    border-radius:6px;
                    cursor:pointer;
                    font-weight:bold;
                    font-size:18px;
                    box-sizing:border-box;
                    line-height:30px;
                "
            >
                Search
            </button>

            <?php if ($search !== ''): ?>
                <a 
                    href="products.php" 
                    style="
                        width:60px;
                        height:40px;
                        padding:0;
                        background:#ff4d4d;
                        color:white;
                        text-decoration:none;
                        border-radius:6px;
                        font-weight:bold;
                        font-size:24px;
                        display:flex;
                        align-items:center;
                        justify-content:center;
                        box-sizing:border-box;
                        line-height:60px;
                    "
                >
                    X
                </a>
            <?php endif; ?>

        </form>
    </div>

    <?php if ($search !== ''): ?>
        <h3 style="color:white; text-align:center; font-family:sans-serif;">
            Rezultatet për: "<?php echo e($search); ?>"
        </h3>
    <?php endif; ?>

    <div style="max-width:760px; margin:0 auto 25px auto; background:rgba(255,255,255,0.12); color:white; padding:18px; border-radius:12px; text-align:center; font-family:sans-serif;">
        <h3 style="margin:0 0 8px 0;">Kurs valutor për çmimet</h3>

        <p id="exchange-info" style="margin:0 0 12px 0; opacity:0.9;">
            Kliko butonin për t’i konvertuar çmimet nga EUR në USD duke përdorur Web API të jashtme.
        </p>

        <button
            id="convert-usd-btn"
            type="button"
            style="background:#3498db; color:white; border:none; padding:10px 18px; border-radius:6px; cursor:pointer; font-weight:bold;"
        >
            Shfaq çmimet në USD
        </button>

        <button
            id="reset-eur-btn"
            type="button"
            style="background:#7f8c8d; color:white; border:none; padding:10px 18px; border-radius:6px; cursor:pointer; font-weight:bold; margin-left:8px;"
        >
            Kthe EUR
        </button>
    </div>

    <?php if (empty($products)): ?>

        <h3 style="color:white; text-align:center;">
            Nuk u gjet asnjë produkt.
        </h3>

    <?php else: ?>

        <?php foreach ($categories as $category): ?>

            <?php
            $hasProductsInCategory = false;

            foreach ($products as $checkProduct) {
                if ($checkProduct['category_slug'] === $category['slug']) {
                    $hasProductsInCategory = true;
                    break;
                }
            }
            ?>

            <?php if ($hasProductsInCategory): ?>

                <h2 style="color:white; margin-left:20px; font-family:sans-serif;">
                    <?php echo e($category['name']); ?>
                </h2>

                <div class="category-row" style="display:flex; overflow-x:auto; gap:15px; padding:20px;">

                    <?php foreach ($products as $p): ?>

                        <?php if ($p['category_slug'] === $category['slug']): ?>

                            <div 
                                class="card" 
                                id="product-<?php echo (int)$p['id']; ?>" 
                                style="background:white; min-width:200px; padding:15px; border-radius:12px; text-align:center;"
                            >

                                <div style="width:100%; height:130px; margin-bottom:10px;">
                                    <img 
                                        src="<?php echo e($p['image'] ?: 'https://via.placeholder.com/200'); ?>" 
                                        style="width:100%; height:100%; object-fit:contain;" 
                                        alt="<?php echo e($p['name']); ?>"
                                    >
                                </div>

                                <h3 style="color:#2c3e50; margin:5px 0; font-size:18px;">
                                    <?php echo e($p['name']); ?>
                                </h3>

                                <p
                                    class="product-price"
                                    data-eur="<?php echo number_format((float)$p['price'], 2, '.', ''); ?>"
                                    style="color:#e67e22; font-weight:bold; margin:8px 0;"
                                >
                                    <?php echo number_format((float)$p['price'], 2); ?> €
                                </p>

                                <?php if (isAdmin()): ?>

                                    <form method="POST" action="products.php" style="margin:0 0 8px 0;">
                                        <input type="hidden" name="product_id" value="<?php echo (int)$p['id']; ?>">

                                        <button 
                                            type="submit"
                                            name="add_to_cart"
                                            style="background:#2ecc71; color:white; border:none; padding:8px 20px; border-radius:5px; cursor:pointer; width:100%;"
                                        >
                                            Add to Cart
                                        </button>
                                    </form>

                                    <button 
                                        class="delete-product-btn" 
                                        data-id="<?php echo (int)$p['id']; ?>" 
                                        style="background:#ff4d4d; color:white; border:none; padding:8px 12px; border-radius:5px; cursor:pointer; width:100%;"
                                    >
                                        Delete
                                    </button>

                                <?php else: ?>

                                    <form method="POST" action="products.php" style="margin:0;">
                                        <input type="hidden" name="product_id" value="<?php echo (int)$p['id']; ?>">

                                        <button 
                                            type="submit"
                                            name="add_to_cart"
                                            style="background:#2ecc71; color:white; border:none; padding:8px 20px; border-radius:5px; cursor:pointer; width:100%;"
                                        >
                                            Buy
                                        </button>
                                    </form>

                                <?php endif; ?>

                            </div>

                        <?php endif; ?>

                    <?php endforeach; ?>

                </div>

            <?php endif; ?>

        <?php endforeach; ?>

    <?php endif; ?>

</div>

<script>
const convertUsdBtn = document.getElementById('convert-usd-btn');
const resetEurBtn = document.getElementById('reset-eur-btn');
const exchangeInfo = document.getElementById('exchange-info');

if (convertUsdBtn) {
    convertUsdBtn.addEventListener('click', function () {
        convertUsdBtn.disabled = true;
        exchangeInfo.textContent = 'Duke marrë kursin valutor nga Web API...';

        fetch('api/exchange_rate.php')
            .then(response => response.json())
            .then(data => {
                if (!data.success) {
                    throw new Error(data.message || 'Gabim gjatë marrjes së kursit valutor.');
                }

                document.querySelectorAll('.product-price').forEach(priceElement => {
                    const eur = parseFloat(priceElement.dataset.eur);
                    const usd = eur * data.rate;
                    priceElement.textContent = usd.toFixed(2) + ' $';
                });

                exchangeInfo.textContent = '1 EUR = ' + data.rate.toFixed(4) + ' USD | Data: ' + data.date;
            })
            .catch(error => {
                exchangeInfo.textContent = error.message || 'Nuk mund të bëhet konvertimi për momentin.';
            })
            .finally(() => {
                convertUsdBtn.disabled = false;
            });
    });
}

if (resetEurBtn) {
    resetEurBtn.addEventListener('click', function () {
        document.querySelectorAll('.product-price').forEach(priceElement => {
            const eur = parseFloat(priceElement.dataset.eur);
            priceElement.textContent = eur.toFixed(2) + ' €';
        });

        exchangeInfo.textContent = 'Çmimet u kthyen në EUR.';
    });
}
</script>

<?php if (isAdmin()): ?>
<script>
document.querySelectorAll('.delete-product-btn').forEach(button => {
    button.addEventListener('click', function () {
        if (!confirm('A je i sigurt që dëshiron ta fshish këtë produkt?')) {
            return;
        }

        const id = this.dataset.id;
        const formData = new FormData();
        formData.append('id', id);

        fetch('api/delete_product.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const card = document.getElementById('product-' + id);
                if (card) {
                    card.remove();
                }
            } else {
                alert(data.message || 'Produkti nuk u fshi.');
            }
        })
        .catch(() => {
            alert('Gabim në AJAX.');
        });
    });
});
</script>
<?php endif; ?>

<?php include "includes/footer.php"; ?> 