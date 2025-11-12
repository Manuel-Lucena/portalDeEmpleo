<?php
header('Content-Type: application/json');
require_once '../Downloads/mi_autoload.php';
use Repositorys\RepoFamilia;

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
        http_response_code(405);
        echo json_encode(["error" => "Método no permitido"]);
        exit;
    }

    $familias = RepoFamilia::findAll();
    $arr = [];
    foreach ($familias as $f) {
        $arr[] = [
            "id" => $f->getId(),
            "nombre" => $f->getNombre()
        ];
    }
    echo json_encode($arr);

} catch (\Exception $e) {
    http_response_code(500);
    echo json_encode(["error" => $e->getMessage()]);
}
?>
