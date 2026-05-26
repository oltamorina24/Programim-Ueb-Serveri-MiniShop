<?php
require_once "includes/init.php";

if (!isLoggedIn()) {
    redirect('login.php');
}

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_POST['remove_item'])) {
        $productId = filter_input(INPUT_POST, 'product_id', FILTER_VALIDATE_INT);

        if ($productId && isset($_SESSION['cart'][$productId])) {
            unset($_SESSION['cart'][$productId]);
        }

        redirect('cart.php');
    }

    if (isset($_POST['checkout'])) {
        if (empty($_SESSION['cart'])) {
            $error = 'Shporta është e zbrazët.';
        } else {
            try {
                $total = 0;

                foreach ($_SESSION['cart'] as $item) {
                    $total += $item['price'] * $item['quantity'];
                }

                $pdo->beginTransaction();

                $stmt = $pdo->prepare("INSERT INTO orders (user_id, total, status) VALUES (?, ?, ?)");
                $stmt->execute([
                    $_SESSION['user']['id'],
                    $total,
                    'pending'
                ]);

                $orderId = (int)$pdo->lastInsertId();

                $stmt = $pdo->prepare("
                    INSERT INTO order_items (order_id, product_id, quantity, price)
                    VALUES (?, ?, ?, ?)
                ");

                foreach ($_SESSION['cart'] as $item) {
                    $stmt->execute([
                        $orderId,
                        $item['id'],
                        $item['quantity'],
                        $item['price']
                    ]);
                }

                $pdo->commit();

                $_SESSION['cart'] = [];

                redirect('orders.php');

            } catch (PDOException $e) {
                if ($pdo->inTransaction()) {
                    $pdo->rollBack();
                }

                $error = 'Gabim gjatë krijimit të porosisë.';
            }
        }
    }
}

include "includes/header.php";
include "includes/nav.php";
?>

<div class="container" style="padding:40px; color:white; min-height:70vh;">
    <h2 style="text-align:center;">Shporta ime</h2>

    <?php if ($error): ?>
        <div style="max-width:760px; margin:20px auto; background:#ff4d4d; color:white; padding:12px; border-radius:8px; text-align:center;">
            <?php echo e($error); ?>
        </div>
    <?php endif; ?>

    <?php if (empty($_SESSION['cart'])): ?>

        <p style="text-align:center;">Shporta është e zbrazët.</p>

        <div style="text-align:center; margin-top:20px;">
            <a href="products.php" style="background:#2ecc71; color:white; padding:10px 18px; border-radius:6px; text-decoration:none;">
                Kthehu te produktet
            </a>
        </div>

    <?php else: ?>

        <?php $total = 0; ?>

        <div style="max-width:900px; margin:0 auto; display:flex; flex-direction:column; gap:15px;">

            <?php foreach ($_SESSION['cart'] as $item): ?>
                <?php $subtotal = $item['price'] * $item['quantity']; ?>
                <?php $total += $subtotal; ?>

                <div style="background:rgba(255,255,255,0.12); border-radius:12px; padding:15px; display:flex; align-items:center; gap:15px;">

                    <img 
                        src="<?php echo e($item['image'] ?: 'https://via.placeholder.com/80'); ?>" 
                        alt="<?php echo e($item['name']); ?>" 
                        style="width:80px; height:80px; object-fit:contain; background:white; border-radius:8px;"
                    >

                    <div style="flex:1;">
                        <h3 style="margin:0 0 8px 0;">
                            <?php echo e($item['name']); ?>
                        </h3>

                        <p style="margin:3px 0;">
                            Çmimi: <?php echo number_format((float)$item['price'], 2); ?> €
                        </p>

                        <p style="margin:3px 0;">
                            Sasia: <?php echo (int)$item['quantity']; ?>
                        </p>

                        <p style="margin:3px 0;">
                            Subtotali: <?php echo number_format((float)$subtotal, 2); ?> €
                        </p>
                    </div>

                    <form method="POST" action="cart.php">
                        <input type="hidden" name="product_id" value="<?php echo (int)$item['id']; ?>">

                        <button 
                            type="submit" 
                            name="remove_item"
                            style="background:#ff4d4d; color:white; border:none; padding:8px 12px; border-radius:5px; cursor:pointer;"
                        >
                            Largo
                        </button>
                    </form>

                </div>

            <?php endforeach; ?>

            <div style="background:rgba(255,255,255,0.18); border-radius:12px; padding:20px; text-align:right;">
                <h3>Totali: <?php echo number_format((float)$total, 2); ?> €</h3>

                <form method="POST" action="cart.php">
                    <button 
                        type="submit" 
                        name="checkout"
                        style="background:#2ecc71; color:white; border:none; padding:12px 20px; border-radius:6px; cursor:pointer; font-size:16px;"
                    >
                        Checkout / Porosit
                    </button>
                </form>
            </div>

        </div>

    <?php endif; ?>
</div>

<?php include "includes/footer.php"; ?>