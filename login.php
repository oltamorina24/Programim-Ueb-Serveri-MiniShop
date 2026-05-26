<?php
require_once "includes/init.php";

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Email nuk është në formatin e duhur!";
    } elseif (strlen($password) < 6) {
        $error = "Password-i duhet të ketë të paktën 6 karaktere!";
    } else {
        try {
            $stmt = $pdo->prepare("SELECT id, name, email, password, role FROM users WHERE email = ? LIMIT 1");
            $stmt->execute([$email]);
            $user = $stmt->fetch();

            if ($user && password_verify($password, $user['password'])) {
                session_regenerate_id(true);
                $_SESSION['user'] = [
                    'id' => $user['id'],
                    'name' => $user['name'],
                    'email' => $user['email'],
                    'role' => $user['role']
                ];

                setcookie("username", $user['name'], time() + (86400 * 30), "/", "", false, true);
                redirect("index.php");
            } else {
                $error = "Email ose Password i gabuar!";
            }
        } catch (PDOException $e) {
            $error = "Gabim gjatë kyçjes. Provo përsëri.";
        }
    }
}

include "includes/header.php";
include "includes/nav.php";
?>

<div class="login-container" style="display:flex; justify-content:center; align-items:center; min-height:70vh;">
    <div style="background:rgba(255,255,255,0.1); backdrop-filter:blur(15px); padding:40px; border-radius:20px; box-shadow:0 15px 35px rgba(0,0,0,0.2); width:350px; border:1px solid rgba(255,255,255,0.1); text-align:center;">
        <h2 style="color:white; margin-bottom:30px; font-family:sans-serif;">Kyçja në MiniShop</h2>

        <?php if ($error): ?>
            <div style="background:#ff4d4d; color:white; padding:10px; border-radius:5px; margin-bottom:20px; font-size:14px;">
                <?php echo e($error); ?>
            </div>
        <?php endif; ?>

        <form method="POST" autocomplete="off">
            <div style="margin-bottom:20px; text-align:left;">
                <label style="color:white; display:block; margin-bottom:5px; font-size:14px;">Email</label>
                <input type="email" name="email" placeholder="email@shembull.com" required value="<?php echo e($_POST['email'] ?? ''); ?>" style="width:100%; padding:12px; border-radius:8px; border:none; background:rgba(255,255,255,0.2); color:white; outline:none; box-sizing:border-box;">
            </div>

            <div style="margin-bottom:30px; text-align:left;">
                <label style="color:white; display:block; margin-bottom:5px; font-size:14px;">Password</label>
                <input type="password" name="password" placeholder="••••••" required style="width:100%; padding:12px; border-radius:8px; border:none; background:rgba(255,255,255,0.2); color:white; outline:none; box-sizing:border-box;">
            </div>

            <button type="submit" style="width:100%; padding:12px; border-radius:8px; border:none; background:#2ecc71; color:white; font-weight:bold; font-size:16px; cursor:pointer;">
                Login
            </button>
        </form>

        <p style="color:white; margin-top:20px; font-size:13px; opacity:0.8;">
            Nuk keni llogari? <a href="register.php" style="color:#2ecc71; text-decoration:none;">Regjistrohuni</a>
        </p>
    </div>
</div>

<?php include "includes/footer.php"; ?>