<?php
require_once "includes/init.php";

if (!isLoggedIn()) {
    redirect('login.php');
}

try {
    $stmt = $pdo->prepare("
        SELECT 
            o.id AS order_id,
            o.total,
            o.status,
            o.created_at,
            oi.quantity,
            oi.price,
            p.name AS product_name,
            p.image AS product_image
        FROM orders o
        INNER JOIN order_items oi ON oi.order_id = o.id
        INNER JOIN products p ON p.id = oi.product_id
        WHERE o.user_id = ?
        ORDER BY o.created_at DESC, o.id DESC
    ");

    $stmt->execute([$_SESSION['user']['id']]);
    $orders = $stmt->fetchAll();

} catch (PDOException $e) {
    $orders = [];
    $error = "Gabim gjatë leximit të porosive.";
}

include "includes/header.php";
include "includes/nav.php";
?>

<div class="container" style="padding:40px; color:white; min-height:70vh;">
    <h2 style="text-align:center;">Porositë e mia</h2>

    <?php if (!empty($error)): ?>
        <div style="max-width:760px; margin:20px auto; background:#ff4d4d; color:white; padding:12px; border-radius:8px; text-align:center;">
            <?php echo e($error); ?>
        </div>
    <?php endif; ?>

    <?php if (empty($orders)): ?>

        <p style="text-align:center;">Ende nuk keni bërë asnjë porosi.</p>

    <?php else: ?>

        <div style="max-width:900px; margin:0 auto; display:flex; flex-direction:column; gap:15px;">

            <?php foreach ($orders as $order): ?>

                <div style="background:rgba(255,255,255,0.12); border-radius:12px; padding:15px; display:flex; align-items:center; gap:15px;">

                    <img 
                        src="<?php echo e($order['product_image'] ?: 'https://via.placeholder.com/80'); ?>" 
                        alt="<?php echo e($order['product_name']); ?>" 
                        style="width:80px; height:80px; object-fit:contain; background:white; border-radius:8px;"
                    >

                    <div style="flex:1;">
                        <h3 style="margin:0 0 8px 0;">
                            <?php echo e($order['product_name']); ?>
                        </h3>

                        <p style="margin:3px 0;">
                            Porosia #<?php echo (int)$order['order_id']; ?> | 
                            Sasia: <?php echo (int)$order['quantity']; ?>
                        </p>

                        <p style="margin:3px 0;">
                            Çmimi: <?php echo number_format((float)$order['price'], 2); ?> € | 
                            Totali: <?php echo number_format((float)$order['total'], 2); ?> €
                        </p>

                        <p style="margin:3px 0;">
                            Statusi: <?php echo e($order['status']); ?> | 
                            Data: <?php echo e($order['created_at']); ?>
                        </p>
                    </div>

                </div>

            <?php endforeach; ?>

        </div>

    <?php endif; ?>
</div>

<?php include "includes/footer.php"; ?>