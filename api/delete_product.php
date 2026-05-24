<?php
require_once __DIR__ . '/../includes/init.php';
header('Content-Type: application/json; charset=utf-8');

if(!isAdmin()){
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Nuk keni leje per kete veprim.']);
    exit();
}

if($_SERVER['REQUEST_METHOD'] !== 'POST'){
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Metoda nuk lejohet.']);
    exit();
}

$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
if(!$id){
    echo json_encode(['success' => false, 'message' => 'ID e produktit nuk eshte valide.']);
    exit();
}
try{
    $stmt = $pdo->prepare('DELETE FROM products WHERE id = ?');
    $stmt->execute([$id]);

    if($stmt -> rowCount() > 0){
        echo json_encode(['success' => true, 'message' => 'Produkti u fshi me sukses.']);
    }else{
        echo json_encode(['success' => false, 'message' => 'Produkti nuk u gjet.']);
    }
}catch(PDOException $e){
    echo json_encode(['success' => false, 'message' => 'Gabim ne databaze.']);
}
?>