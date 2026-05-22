<?php
function e($value) {
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

function redirect($path) {
    header('Location: ' . $path);
    exit();
}

function isLoggedIn() {
    return isset($_SESSION['user']);
}

function isAdmin() {
    return isset($_SESSION['user']['role']) && $_SESSION['user']['role'] === 'admin';
}

function requireAdmin() {
    if (!isAdmin()) {
        redirect('index.php');
    }
}

function validateProductInput($name, $price, $categoryId) {
    $errors = [];
    if (mb_strlen(trim($name)) < 2 || mb_strlen(trim($name)) > 100) {
        $errors[] = 'Emri i produktit duhet të ketë 2-100 karaktere.';
    }
    if (!is_numeric($price) || (float)$price <= 0) {
        $errors[] = 'Çmimi duhet të jetë numër pozitiv.';
    }
    if (!filter_var($categoryId, FILTER_VALIDATE_INT)) {
        $errors[] = 'Kategoria nuk është valide.';
    }
    return $errors;
}

function handleProductImageUpload($fieldName, $oldImage = '') {
    if (!isset($_FILES[$fieldName]) || $_FILES[$fieldName]['error'] === UPLOAD_ERR_NO_FILE) {
        return $oldImage;
    }

    if ($_FILES[$fieldName]['error'] !== UPLOAD_ERR_OK) {
        throw new Exception('Ngarkimi i fotos dështoi.');
    }

    if ($_FILES[$fieldName]['size'] > 2 * 1024 * 1024) {
        throw new Exception('Fotoja duhet të jetë më e vogël se 2MB.');
    }

    $allowed = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp', 'image/gif' => 'gif'];
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime = finfo_file($finfo, $_FILES[$fieldName]['tmp_name']);
   
    if (!isset($allowed[$mime])) {
        throw new Exception('Lejohen vetëm fotot JPG, PNG, WEBP ose GIF.');
    }

    $uploadDir = __DIR__ . '/../uploads/products/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0775, true);
    }

    $fileName = uniqid('product_', true) . '.' . $allowed[$mime];
    $target = $uploadDir . $fileName;

    if (!move_uploaded_file($_FILES[$fieldName]['tmp_name'], $target)) {
        throw new Exception('Fotoja nuk u ruajt në folder.');
    }

    return 'uploads/products/' . $fileName;
}