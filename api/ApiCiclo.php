<?php
header('Content-Type: application/json');
require_once '../Downloads/mi_autoload.php';

use Repositorys\RepoCiclo;
use Models\Ciclo;

$statusCode = 200;
$response = [];

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
        $statusCode = 405;
        $response = ["mensaje" => "Acción no permitida"];
    } else {
        $idFamilia = $_GET['idFamilia'] ?? null;

        if ($idFamilia === null) {
            $statusCode = 400;
            $response = ["error" => "Falta el parámetro idFamilia"];
        } else {
            $ciclos = RepoCiclo::findByFamilia($idFamilia);
            $data = [];

            foreach ($ciclos as $c) {
                $data[] = [
                    'id' => $c->getId(),
                    'nombre' => $c->getNombre(),
                    'tipo' => $c->getTipo(),
                    'idFamilia' => $c->getIdFamilia()
                ];
            }

            $response = $data;
        }
    }
} catch (Exception $e) {
    $statusCode = 500;
    $response = ["error" => $e->getMessage()];
}

http_response_code($statusCode);
echo json_encode($response);
