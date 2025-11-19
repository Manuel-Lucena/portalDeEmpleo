<?php
header('Content-Type: application/json');
require_once '../Downloads/mi_autoload.php';

use Repositorys\RepoFamilia;

$statusCode = 200;
$response = [];

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
        $statusCode = 405;
        $response = ["error" => "Método no permitido"];
    } else {
        $familias = RepoFamilia::findAll();
        $arr = [];

        foreach ($familias as $f) {
            $arr[] = [
                "id" => $f->getId(),
                "nombre" => $f->getNombre()
            ];
        }

        $response = $arr;
    }
} catch (Exception $e) {
    $statusCode = 500;
    $response = ["error" => $e->getMessage()];
}

http_response_code($statusCode);
echo json_encode($response);
