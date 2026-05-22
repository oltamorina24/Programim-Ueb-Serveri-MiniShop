<?php

require_once "includes/init.php";

include "includes/header.php";
include "includes/nav.php";

$message = "";
$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST"){
    $fullname = trim($_POST['fullname'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if(!preg_match("/^[a-zA-ZÀ-ž\s]{4,100}$/u", $fullname) || substr_count(trim($fullname), ' ') < 1){
        $error = "Shënoni emrin dhe mbiemrin e plotë!";
    }
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)){
        $error = "Formati i email-it nuk eshte i sakte!";
    }
    elseif (strlen($password) < 6){
        $error = "Password-i duhet te kete te pakten 6 karaktere!";
    }
    else{
        try {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, 'user')");
            $stmt->execute([$fullname, $email, $hashedPassword]);
            $message = "Llogaria u krijua me sukses! Mund te kyceni tani.";
        } catch (PDOException $e) {
            if ($e->getCode() === '23000') {
                $error = "Ky email ekziston tashmë.";
            } else {
                $error = "Gabim gjatë regjistrimit.";
            }
        }
    }
}
?>

<div class="login-container" style="display: flex; justify-content: center; align-items: center; min-height: 80vh;">
    <div style="background: rgba(255, 255, 255, 0.1); backdrop-filter: blur(15px); padding: 40px; border-radius: 20px; box-shadow: 0 15px 35px rgba(0,0,0,0.2); width: 400px; border: 1px solid rgba(255,255,255,0.1); text-align: center;">
        
        <h2 style="color: white; margin-bottom: 25px;">Krijo Llogari</h2>

        <?php if($error): ?>
            <div style="background: #ff4d4d; color: white; padding: 10px; border-radius: 5px; margin-bottom: 20px; font-size: 14px;">
                <?php echo e($error); ?>
            </div>
        <?php endif; ?>

        <?php if($message): ?>
            <div style="background: #2ecc71; color: white; padding: 10px; border-radius: 5px; margin-bottom: 20px; font-size: 14px;">
                <?php echo e($message); ?>
                <br><a href="login.php" style="color: white; font-weight: bold;">Kliko këtu për Login</a>
            </div>
        <?php endif; ?>

        <form method="POST" autocomplete="off">
            <div style="margin-bottom: 15px; text-align: left;">
                <label style="color: white; display: block; margin-bottom: 5px; font-size: 14px;">Emri i Plotë</label>
                <input type="text" name="fullname" required placeholder="Filan Fisteku" 
                       value="<?php echo e($_POST['fullname'] ?? ''); ?>" 
                       style="width: 100%; padding: 12px; border-radius: 8px; border: none; background: rgba(255,255,255,0.2); color: white; outline: none; box-sizing:border-box;">
            </div>

            <div style="margin-bottom: 15px; text-align: left;">
                <label style="color: white; display: block; margin-bottom: 5px; font-size: 14px;">Email</label>
                <input type="email" name="email" required placeholder="email@shembull.com" 
                       value="<?php echo e($_POST['email'] ?? ''); ?>" 
                       style="width: 100%; padding: 12px; border-radius: 8px; border: none; background: rgba(255,255,255,0.2); color: white; outline: none; box-sizing:border-box;">
            </div>

            <div style="margin-bottom: 25px; text-align: left;">
                <label style="color: white; display: block; margin-bottom: 5px; font-size: 14px;">Password</label>
                <input type="password" name="password" required placeholder="Min. 6 karaktere" 
                       style="width: 100%; padding: 12px; border-radius: 8px; border: none; background: rgba(255,255,255,0.2); color: white; outline: none; box-sizing:border-box;">
            </div>

            <button type="submit" style="width: 100%; padding: 12px; border-radius: 8px; border: none; background: #3498db; color: white; font-weight: bold; font-size: 16px; cursor: pointer;">
                Regjistrohu
            </button>
        </form>
        
    </div>
</div>

<?php include "includes/footer.php"; ?>