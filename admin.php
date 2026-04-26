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
}
?>