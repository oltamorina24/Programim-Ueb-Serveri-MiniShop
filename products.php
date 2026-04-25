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

echo "<div class = 'container'>";

foreach($categories as $key => $label){
    echo "<h2 style = 'color:white; margin-left:20px;'>$label</h2>";
    echo "<div class = 'category-row'>";

    foreach($products as $data){
        if(isset($data['category']) && $data['category'] == $key){
            $p = new Product($data['name'], $data['price'], $data['category']);

            echo "<div class = 'card'>";
            echo "<h3>" . $p -> getName() . "</h3>";
            echo "<p>" . $p -> getPrice() . "€</p>";
            echo "<button>Buy</button>";
            echo "</div>";
        }
    }
    echo "</div>";
}
echo "</div>";

include "includes/footer.php";
?>