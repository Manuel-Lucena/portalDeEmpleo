<?php
header('Content-Type: application/json');

require_once __DIR__ . "/../vendor/autoload.php";
require_once '../Downloads/mi_autoload.php';

use Helpers\Login;
use Helpers\Sesion;
use Helpers\Security;
use Repositorys\RepoEmpresa;

Sesion::abrirsesion();

switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        getEstadisticas();
        break;

    default:
        http_response_code(405);
        echo json_encode(["mensaje" => "Método no permitido"]);
        break;
}

function getEstadisticas()
{
    $usuario = Security::verificarToken();
    $statusCode = 200;
    $response = [];

    try {
        $rol = Login::getRol();
        $idUser = Login::getId();

        if ($rol != 2) { 
            $statusCode = 403;
            $response = ["error" => "Solo empresas pueden ver estadísticas"];
        } else {
            $empresa = RepoEmpresa::findByUserId($idUser);
            $idEmpresa = $empresa->getId();

   
            $totalOfertas = RepoEmpresa::contarOfertas($idEmpresa);
            $totalSolicitudes = RepoEmpresa::contarSolicitudes($idEmpresa);
            $solicitudesPorEstado = RepoEmpresa::contarSolicitudesPorEstado($idEmpresa);
            $solicitudesPorOferta = RepoEmpresa::contarSolicitudesPorOferta($idEmpresa);

            $labelsEstado = [];
            $dataEstado = [];
            foreach ($solicitudesPorEstado as $fila) {
                $labelsEstado[] = $fila['estado'];
                $dataEstado[] = $fila['total'];
            }

            $labelsOferta = [];
            $dataOferta = [];
            foreach ($solicitudesPorOferta as $fila) {
                $labelsOferta[] = $fila['titulo'];
                $dataOferta[] = $fila['total'];
            }

            $response = [
                'totalOfertas' => $totalOfertas,
                'totalSolicitudes' => $totalSolicitudes,
                'solicitudesPorEstado' => [
                    'labels' => $labelsEstado,
                    'data' => $dataEstado
                ],
                'solicitudesPorOferta' => [
                    'labels' => $labelsOferta,
                    'data' => $dataOferta
                ]
            ];
        }

    } catch (Exception $e) {
        $statusCode = 500;
        $response = [
            "error" => "Error al obtener estadísticas",
            "detalle" => $e->getMessage()
        ];
    }

    http_response_code($statusCode);
    echo json_encode($response);
}
