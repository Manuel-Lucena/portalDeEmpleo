<?php
header('Content-Type: application/json');


require_once __DIR__ . "/../vendor/autoload.php";
require_once '../Downloads/mi_autoload.php';

use Helpers\Login;
use Helpers\Sesion;
use Models\Solicitud;
use Repositorys\RepoSolicitud;
use Repositorys\RepoOferta;
use Helpers\Security;
use Repositorys\RepoAlumno;
use Repositorys\RepoEmpresa;
use Services\MailServices;

Sesion::abrirsesion();

switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        getSolicitudes();
        break;

    case 'POST':
        postSolicitud();
        break;

    case 'PUT':
        putSolicitud();
        break;

    default:
        http_response_code(405);
        echo json_encode(["mensaje" => "Método no permitido"]);
        break;
}




function getSolicitudes()
{
      $usuario = Security::verificarToken();
    $statusCode = 200;
    $response = [];

    try {

        $usuario = Security::verificarToken();

        $rol = Login::getRol();
        $idUser = Login::getId();

        $solicitudes = [];

        if ($rol == 1) {

            $solicitudes = RepoSolicitud::findAll();
        } elseif ($rol == 2) {

            $ofertas = RepoOferta::findByEmpresa($idUser);
            foreach ($ofertas as $oferta) {
                $sols = RepoSolicitud::findByOferta($oferta->getId());
                foreach ($sols as $s) {
                    $solicitudes[] = $s;
                }
            }
        } elseif ($rol == 3) {

            $solicitudes = RepoSolicitud::findByAlumno($idUser);
        } else {
            $statusCode = 403;
            $response = ["error" => "Rol no permitido"];
        }

        if (empty($response)) {

            $response = array_map(function ($s) {
                $oferta = RepoOferta::findById($s->getIdOferta());
                $empresa = RepoEmpresa::findById($oferta->getIdEmpresa());
                $alumno = RepoAlumno::findByIdUser($s->getIdAlumno());
                $rol = Login::getRol();
                return [
                    'id' => $s->getId(),
                    'idAlumno' => $alumno->getId(),
                    'alumno' => $alumno->getNombre(),
                    'idOferta' => $oferta->getId(),
                    'oferta' => $oferta->getTitulo(),
                    'empresa' => $empresa->getNombreEmpresa(),
                    'fechaSolicitud' => $s->getFechaSolicitud(),
                    'estado' => $s->getEstado(),
                    'favorito' => $s->getFavorito(),
                    'rolUsuario' => $rol
                ];
            }, $solicitudes);
        }
    } catch (Exception $e) {
        $statusCode = 500;
        $response = ["error" => "Error al obtener solicitudes", "detalle" => $e->getMessage()];
    }

    http_response_code($statusCode);
    echo json_encode($response);
}

function postSolicitud()
{
    $usuario = Security::verificarToken();
    $statusCode = 200;
    $response = [];

    try {
        $data = json_decode(file_get_contents('php://input'), true);

        $idAlumno = $data['idAlumno'] ?? null;
        $idOferta = $data['idOferta'] ?? null;

        if (!$idAlumno || !$idOferta) {
            $statusCode = 400;
            $response = ["error" => "Faltan datos obligatorios (idAlumno o idOferta)"];
        } else {
       
            $existente = RepoSolicitud::findByAlumnoYOferta($idAlumno, $idOferta);
            if ($existente) {
                $statusCode = 400;
                $response = ["error" => "Ya has solicitado esta oferta."];
            } else {
                $fechaSolicitud = date('Y-m-d H:i:s');
                $estado = "pendiente";

                $solicitud = new Solicitud($idAlumno, $idOferta, $fechaSolicitud, $estado);
                $solicitud->setFavorito(0);
                RepoSolicitud::save($solicitud);

                $response = [
                    "mensaje" => "Solicitud creada correctamente",
                    "id" => $solicitud->getId(),
                    "idAlumno" => $solicitud->getIdAlumno(),
                    "idOferta" => $solicitud->getIdOferta(),
                    "fechaSolicitud" => $solicitud->getFechaSolicitud(),
                    "estado" => $solicitud->getEstado()
                ];
            }
        }
    } catch (Exception $e) {
        $statusCode = 500;
        $response = [
            "error" => "Error al crear la solicitud",
            "detalle" => $e->getMessage()
        ];
    }

    http_response_code($statusCode);
    echo json_encode($response);
}


function putSolicitud()
{

    $usuario = Security::verificarToken();
    $statusCode = 200;
    $response = [];

    try {
        $data = json_decode(file_get_contents('php://input'), true);
        $idSolicitud = $data['idSolicitud'] ?? null;
        $estado = $data['estado'] ?? null;
        $favorito = $data['favorito'] ?? null;

        if (!$idSolicitud || ($estado === null && $favorito === null)) {
            $statusCode = 400;
            $response = ["error" => "Faltan datos"];
        } else {
            $solicitud = RepoSolicitud::findById($idSolicitud);
            if (!$solicitud) {
                $statusCode = 404;
                $response = ["error" => "Solicitud no encontrada"];
            } else {

                if ($estado !== null) {
                    $solicitud->setEstado($estado);


                    if ($estado === 'aceptada') {
                        $alumno = RepoAlumno::findByIdUser($solicitud->getIdAlumno());
                        $oferta = RepoOferta::findById($solicitud->getIdOferta());
                        $empresa = RepoEmpresa::findById($oferta->getIdEmpresa());

                        if ($alumno && $oferta && $empresa) {
                            MailServices::enviarCorreoSolicitudAceptada($alumno, $oferta, $empresa);
                        }
                    }
                }


                if ($favorito !== null) {
                    $solicitud->setFavorito($favorito);
                }

                RepoSolicitud::update($solicitud);

                $response = [
                    "mensaje" => "Solicitud actualizada correctamente",
                    "id" => $solicitud->getId(),
                    "estado" => $solicitud->getEstado(),
                    "favorito" => $solicitud->getFavorito()
                ];
            }
        }
    } catch (Exception $e) {
        $statusCode = 500;
        $response = [
            "error" => "Error al actualizar la solicitud",
            "detalle" => $e->getMessage()
        ];
    }

    http_response_code($statusCode);
    echo json_encode($response);
}
