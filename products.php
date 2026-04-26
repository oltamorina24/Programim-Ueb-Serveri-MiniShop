<?php
require "data/products.php";
require_once "classes/product.php";

include "includes/header.php";
include "includes/nav.php";

usort($products, function($a, $b) {
    $a['price'] <=> $b['price'];
});

$categories = [
    "drinks" => "Pijet (Drinks) ",
    "sweet" => "Embelsirat (Sweet) ",
    "salt" => "Te kripura (Salt) "
];

echo "<div class = 'container' style = 'padding-top: 20px;'>";

foreach($categories as $key => $label){
    echo "<h2 style='color:white; margin-left:20px; font-family: sans-serif;'>$label</h2>";
    echo "<div class='category-row' style='display: flex; overflow-x: auto; gap: 15px; padding: 20px;'>"; 
    
    foreach($products as $data){
        if(isset($data['category']) && $data['category'] == $key){
            $p = new Product($data['name'], $data['price'], $data['image']);

            echo "<div class='card' style='background: white; min-width: 200px; padding: 15px; border-radius: 12px; text-align: center;'>";
            
            echo "<div style='width: 100%; height: 130px; margin-bottom: 10px;'>";
            echo "<img src='" . $p->getImage() . "' style='width: 100%; height: 100%; object-fit: contain;' alt='Product'>";
            echo "</div>";

            echo "<h3 style='color: #2c3e50; margin: 5px 0;'>" . $p->getName() . "</h3>"; 
            echo "<p style='color: #e67e22; font-weight: bold;'>" . $p->getPrice() . " €</p>";
            echo "<button style='background: #2ecc71; color: white; border: none; padding: 8px 20px; border-radius: 5px; cursor: pointer; width: 100%;'>Buy</button>";
            echo "</div>";
        }
    }
    echo "</div>";
}
echo "</div>";

include "includes/footer.php";
?>