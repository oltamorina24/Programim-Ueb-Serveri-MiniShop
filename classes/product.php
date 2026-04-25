<?php
class Product {
    private $name;
    private $price;
    private $image;

    public function __construct($name, $price, $image) {
        $this->name = $name;
        $this->price = $price;
        $this->image = $image;
    }

    public function getName() { return $this->name; }
    public function getPrice() { return $this->price; }
    public function getImage() { return $this->image; }
}
?>