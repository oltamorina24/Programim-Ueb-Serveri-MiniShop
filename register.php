<?php
session_start();
include "includes/header.php";
include "includes/nav.php";

$message = "";
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST"){
    $fullname = $_POST['fullname'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    if(!preg_match("/^[a-zA-Z]{2,}\s[a-zA-z]{2,}$/", $fullname)){
        $error = "Shenoni emrin dhe mbiemrin e plote!";
    }
    elseif (!preg_match("/^[^@]+@[^@]+\.[a-z]{2,}$/i", $email)){
        $error = "Formati i email-it nuk eshte i sakte!";
    }
    elseif (!preg_match("/^[a-zA-Z0-9]{6,}$/", $password)){
        $error = "Password-i duhet te kete te pakten 6 karaktere!";
    }
    else{
        $message = "Llogaria u krijua me sukses! Mund te kyceni tani.";
    }
}
?>

<div class="login-container" style="display: flex; justify-content: center; align-items: center; min-height: 80vh;">
    <div style="background: rgba(255, 255, 255, 0.1); backdrop-filter: blur(15px); padding: 40px; border-radius: 20px; box-shadow: 0 15px 35px rgba(0,0,0,0.2); width: 400px; border: 1px solid rgba(255,255,255,0.1); text-align: center;">
        
        <h2 style="color: white; margin-bottom: 25px;">Krijo Llogari</h2>

        <?php if($error): ?>
            <div style="background: #ff4d4d; color: white; padding: 10px; border-radius: 5px; margin-bottom: 20px; font-size: 14px;">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <?php if($message): ?>
            <div style="background: #2ecc71; color: white; padding: 10px; border-radius: 5px; margin-bottom: 20px; font-size: 14px;">
                <?php echo $message; ?>
                <br><a href="login.php" style="color: white; font-weight: bold;">Kliko këtu për Login</a>
            </div>
        <?php endif; ?>

        <form method="POST">
            <div style="margin-bottom: 15px; text-align: left;">
                <label style="color: white; display: block; margin-bottom: 5px; font-size: 14px;">Emri i Plotë</label>
                <input type="text" name="fullname" placeholder="Filan Fisteku" 
                       style="width: 100%; padding: 12px; border-radius: 8px; border: none; background: rgba(255,255,255,0.2); color: white; outline: none;">
            </div>

            <div style="margin-bottom: 15px; text-align: left;">
                <label style="color: white; display: block; margin-bottom: 5px; font-size: 14px;">Email</label>
                <input type="text" name="email" placeholder="email@shembull.com" 
                       style="width: 100%; padding: 12px; border-radius: 8px; border: none; background: rgba(255,255,255,0.2); color: white; outline: none;">
            </div>

            <div style="margin-bottom: 25px; text-align: left;">
                <label style="color: white; display: block; margin-bottom: 5px; font-size: 14px;">Password</label>
                <input type="password" name="password" placeholder="Min. 6 karaktere" 
                       style="width: 100%; padding: 12px; border-radius: 8px; border: none; background: rgba(255,255,255,0.2); color: white; outline: none;">
            </div>

            <button type="submit" style="width: 100%; padding: 12px; border-radius: 8px; border: none; background: #3498db; color: white; font-weight: bold; font-size: 16px; cursor: pointer; transition: 0.3s;">
                Regjistrohu
            </button>
        </form>
        
        <p style="color: white; margin-top: 20px; font-size: 13px; opacity: 0.7;">
            Keni llogari? <a href="login.php" style="color: #3498db; text-decoration: none;">Kyçuni këtu</a>
        </p>
    </div>
</div>

<?php include "includes/footer.php"; ?>