<?php
header('Content-Type: application/json; charset=utf-8');

// Web API e jashtme më e përshtatshme për MiniShop:
// Merr kursin aktual EUR -> USD dhe përdoret për konvertimin e çmimeve të produkteve.
$url = 'https://api.frankfurter.app/latest?from=EUR&to=USD';

try {
    $json = @file_get_contents($url);

    if ($json === false) {
        throw new Exception('API e kursit valutor nuk u përgjigj.');
    }

    $data = json_decode($json, true);

    if (!isset($data['rates']['USD'])) {
        throw new Exception('Format i pasaktë nga API e jashtme.');
    }

    echo json_encode([
        'success' => true,
        'base' => 'EUR',
        'target' => 'USD',
        'rate' => (float)$data['rates']['USD'],
        'date' => $data['date'] ?? date('Y-m-d')
    ]);

} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Nuk mund të merret kursi valutor për momentin.'
    ]);
}