<?php
header('Content-Type: application/json');
require_once '../Downloads/mi_autoload.php';
use Repositorys\RepoCiclo;
use Models\Ciclo;



switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        getCiclos();
        break;
    default:
        http_response_code(405);
        echo json_encode(["mensaje" => "Acción no permitida"]);
        break;
}


function getCiclos() {
    try {
        $idFamilia = $_GET['idFamilia'];
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

        echo json_encode($data);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(["error" => $e->getMessage()]);
    }
}

?>
