<?php
session_start();
require "data/users.php"; 

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    if (!preg_match("/^[^@]+@[^@]+\.[a-z]{2,}$/i", $email)) {
        $error = "Email nuk është në formatin e duhur!";
    }
    elseif (!preg_match("/^[a-zA-Z0-9]{6,}$/", $password)) {
        $error = "Password-i duhet të ketë të paktën 6 karaktere!";
    }
    else {
        foreach ($users as $user) {
            if ($user['email'] == $email && $user['password'] == $password) {
                
                $_SESSION['user'] = $user;
                
                setcookie("username", $user['name'], time() + (86400 * 30), "/");
                
                header("Location: index.php");
                exit();
            }
        }
        $error = "Email ose Password i gabuar!";
    }
}
include "includes/header.php";
include "includes/nav.php";
?>