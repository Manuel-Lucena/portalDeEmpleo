<?php
header('Content-Type: application/json');
require_once '../Downloads/mi_autoload.php';

use Models\Alumno;
use Models\User;
use Repositorys\RepoAlumno;
use Models\Estudio;
use Repositorys\RepoUser;
use Repositorys\RepoEstudios;
use Helpers\Validator;
use Helpers\Security;





switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        getAlumnos();
        break;
    case 'POST':
        $input = json_decode(file_get_contents('php://input'), true);
        if (isset($input['alumnos']) && isset($input['cicloId'])) {
            postAlumnosMasivo();
        } else {
            postAlumno();
        }
        break;
    case 'PUT':
        putAlumno();
        break;
    case 'DELETE':
        deleteAlumno();
        break;
    default:
        http_response_code(405);
        echo json_encode(["mensaje" => "Acción no permitida"]);
        break;
}



function getAlumnos()
{
    $usuario = Security::verificarToken();
    $statusCode = 200;
    $response = [];

    try {
        if (isset($_GET['id'])) {
            $alumno = RepoAlumno::findById($_GET['id']);
            if ($alumno) {
                $response = [
                    'id' => $alumno->getId(),
                    'nombre' => $alumno->getNombre(),
                    'email' => $alumno->getEmail(),
                    'fecha_nacimiento' => $alumno->getFechaNacimiento(),
                    'direccion' => $alumno->getDireccion(),
                    'telefono' => $alumno->getTelefono(),
                    'foto' => $alumno->getFoto(),
                    'curriculum' => $alumno->getCurriculum() ? base64_encode($alumno->getCurriculum()) : null
                ];
            } else {
                $statusCode = 404;
                $response = ["error" => "Alumno no encontrado"];
            }
        } elseif (isset($_GET['nombre'])) {
            $nombre = $_GET['nombre'];
            $alumnos = RepoAlumno::findByNombre($nombre);
            $response = array_map(function ($alumno) {
                return [
                    'id' => $alumno->getId(),
                    'nombre' => $alumno->getNombre(),
                    'email' => $alumno->getEmail(),
                    'fecha_nacimiento' => $alumno->getFechaNacimiento(),
                    'direccion' => $alumno->getDireccion(),
                    'telefono' => $alumno->getTelefono()
                ];
            }, $alumnos);
        } elseif (isset($_GET['orden']) && $_GET['orden'] === 'nombre') {
            $alumnos = RepoAlumno::findAllOrderByNombre();
            $response = array_map(function ($alumno) {
                return [
                    'id' => $alumno->getId(),
                    'nombre' => $alumno->getNombre(),
                    'email' => $alumno->getEmail(),
                    'fecha_nacimiento' => $alumno->getFechaNacimiento(),
                    'direccion' => $alumno->getDireccion(),
                    'telefono' => $alumno->getTelefono()
                ];
            }, $alumnos);
        } else {
            $alumnos = RepoAlumno::findAll();
            $response = array_map(function ($alumno) {
                return [
                    'id' => $alumno->getId(),
                    'nombre' => $alumno->getNombre(),
                    'email' => $alumno->getEmail(),
                    'fecha_nacimiento' => $alumno->getFechaNacimiento(),
                    'direccion' => $alumno->getDireccion(),
                    'telefono' => $alumno->getTelefono()
                ];
            }, $alumnos);
        }
    } catch (Exception $e) {
        $statusCode = 500;
        $response = ["error" => "Error al obtener alumnos", "detalle" => $e->getMessage()];
    }

    http_response_code($statusCode);
    echo json_encode($response);
}


function postAlumno()
{
    // $usuario = Security::verificarToken();
    $statusCode = 200;
    $response = [];

    try {

        $nombre = $_POST['nombre'] ?? null;
        $email = $_POST['email'] ?? null;
        $password = $_POST['password'] ?? null;
        $fecha_nacimiento = $_POST['fecha_nacimiento'] ?? null;
        $direccion = $_POST['direccion'] ?? null;
        $telefono = $_POST['telefono'] ?? null;
        $idCiclo = $_POST['idCiclo'] ?? null;
        $fechaInicio = $_POST['fechaInicio'] ?? null;
        $fechaFin = $_POST['fechaFin'] ?? null;
        $curriculum = $_FILES['curriculum'] ?? null;
        $fotoPerfil = $_FILES['fotoPerfil'] ?? null;


        $validator = new Validator();
        $validator->limpiar();


        $validator->obligatorio('nombre', $nombre);
        $validator->obligatorio('email', $email);
        $validator->email('email', $email);
        $validator->obligatorio('password', $password);
        $validator->longitudMinima('password', $password, 6);
        if ($direccion) $validator->longitudMaxima('direccion', $direccion, 100);
        if ($telefono) $validator->telefono('telefono', $telefono);
        if ($fecha_nacimiento) $validator->fecha('fecha_nacimiento', $fecha_nacimiento);
        $validator->obligatorio('idCiclo', $idCiclo);
        $validator->obligatorio('fechaInicio', $fechaInicio);
        $validator->fecha('fechaInicio', $fechaInicio);

        if ($fechaFin) $validator->fecha('fechaFin', $fechaFin);






        if (!$validator->esValido()) {
            $statusCode = 400;
            $response = ['errores' => $validator->getErrores()];
        } else {

            $user = new User($email, password_hash($password, PASSWORD_DEFAULT), 3);
            RepoUser::save($user);


            $curriculumData = $curriculum && $curriculum['tmp_name'] ? file_get_contents($curriculum['tmp_name']) : null;
            $alumno = new Alumno(null, $user->getId(), $nombre, $email, $fecha_nacimiento, $direccion, $telefono, $curriculumData, null);
            RepoAlumno::save($alumno);


            if ($idCiclo) {
                $estudio = new Estudio($alumno->getId(), $idCiclo, $fechaInicio, $fechaFin);
                RepoEstudios::save($estudio);
            }


            if (!empty($fotoPerfil) && !empty($fotoPerfil['tmp_name'])) {

                $rutaFoto = "foto_" . $alumno->getIdUser() . ".png";
                move_uploaded_file($fotoPerfil['tmp_name'], "../fotos/alumno/" . $rutaFoto);
                $alumno->setFoto($rutaFoto);
                RepoAlumno::update($alumno);
            } elseif (!empty($_POST['fotoPerfil'])) {

                $dataUrl = $_POST['fotoPerfil'];
                list($type, $data) = explode(';', $dataUrl);
                list(, $data) = explode(',', $data);
                $data = base64_decode($data);

                $rutaFoto = "foto_" . $alumno->getIdUser() . ".png";
                file_put_contents("../fotos/alumno/" . $rutaFoto, $data);
                $alumno->setFoto($rutaFoto);
                RepoAlumno::update($alumno);
            }


            $response = ["mensaje" => "Alumno creado correctamente", "id" => $alumno->getId(), "foto" => $alumno->getFoto() ?? null];
        }
    } catch (Exception $e) {
        $statusCode = 500;
        $response = ["error" => "No se pudo crear el alumno", "detalle" => $e->getMessage()];
    }

    http_response_code($statusCode);
    echo json_encode($response);
}

function postAlumnosMasivo()
{
    // $usuario = Security::verificarToken();
    $statusCode = 200;
    $response = [];

    try {
        $input = json_decode(file_get_contents('php://input'), true);
        $cicloId = $input['cicloId'] ?? null;
        $alumnos = $input['alumnos'] ?? [];
        if (!$cicloId || empty($alumnos)) {
            $statusCode = 400;
            $response = [
                "success" => false,
                "insertados" => 0,
                "errores" => ["No se seleccionó ciclo o alumnos válidos"]
            ];
        } else {
            $insertados = 0;
            $errores = [];

            foreach ($alumnos as $index => $a) {
                $nombre = $a[0] ?? null;
                $email = $a[1] ?? null;
                $password = $a[2] ?? "123456";
                $fecha_nacimiento = $a[3] ?? null;

                if (!$nombre || !$email) {
                    $errores[] = "Fila $index: faltan datos obligatorios";
                    continue;
                }

                try {
                    $user = new User($email, password_hash($password, PASSWORD_DEFAULT), 3);
                    RepoUser::save($user);

                    $alumno = new Alumno(null, $user->getId(), $nombre, $email, $fecha_nacimiento, null, null, null, null);
                    RepoAlumno::save($alumno);

                    if ($cicloId) {
                        $estudio = new Estudio($alumno->getId(), $cicloId, null, null);
                        RepoEstudios::save($estudio);
                    }

                    $insertados++;
                } catch (Exception $e) {

                    if (strpos($e->getMessage(), 'Integrity constraint violation') !== false && strpos($e->getMessage(), 'Duplicate entry') !== false) {
                        $errores[] = "El correo '$email' ya existe";
                    } else {
                        $errores[] = "Fila $index: " . $e->getMessage();
                    }
                }
            }

            $response = ["success" => true, "insertados" => $insertados, "errores" => $errores];
        }
    } catch (Exception $e) {
        $statusCode = 500;
        $response = ["error" => "Error al procesar la carga masiva", "detalle" => $e->getMessage()];
    }

    http_response_code($statusCode);
    echo json_encode($response);
}

function putAlumno()
{
    $usuario = Security::verificarToken();
    $statusCode = 200;
    $response = [];

    try {
        $data = json_decode(file_get_contents('php://input'), true);
        $id = $data['id'] ?? null;
        $nombre = $data['nombre'] ?? null;
        $email = $data['email'] ?? null;
        $fecha_nacimiento = $data['fecha_nacimiento'] ?? null;
        $direccion = $data['direccion'] ?? null;
        $telefono = $data['telefono'] ?? null;

        if (!$id || !$nombre || !$email) {
            $statusCode = 400;
            $response = ["error" => "Faltan datos obligatorios"];
        } else {
            $alumno = RepoAlumno::findById($id);

            if (!$alumno) {
                $statusCode = 404;
                $response = ["error" => "Alumno no encontrado"];
            } else {
                $alumno->setNombre($nombre);
                $alumno->setEmail($email);
                $alumno->setFechaNacimiento($fecha_nacimiento ?: null);
                $alumno->setDireccion($direccion);
                $alumno->setTelefono($telefono);

                if (!empty($data['curriculum'])) {
                    $alumno->setCurriculum(base64_decode($data['curriculum']));
                }

                if (!empty($data['fotoPerfil'])) {
                    $fotoBinaria = base64_decode($data['fotoPerfil']);
                    $rutaFoto = "foto_" . $alumno->getIdUser() . ".png";
                    file_put_contents("../fotos/alumno/" . $rutaFoto, $fotoBinaria);
                    $alumno->setFoto($rutaFoto);
                }

                RepoAlumno::update($alumno);
                $response = ["success" => true, "mensaje" => "Alumno actualizado correctamente"];
            }
        }
    } catch (Exception $e) {
        $statusCode = 500;
        $response = ["error" => "No se pudo actualizar el alumno", "detalle" => $e->getMessage()];
    }

    http_response_code($statusCode);
    echo json_encode($response);
}

function deleteAlumno()
{
    $usuario = Security::verificarToken();
    $statusCode = 200;
    $response = [];

    try {
        $data = json_decode(file_get_contents('php://input'), true);
        $id = $data['id'] ?? null;

        if (!$id) {
            $statusCode = 400;
            $response = ["error" => "Falta el ID del alumno"];
        } else {
            $resultado = RepoAlumno::delete($id);
            if ($resultado) {
                $response = ["mensaje" => "Alumno eliminado correctamente"];
            } else {
                $statusCode = 404;
                $response = ["error" => "Alumno no encontrado o no eliminado"];
            }
        }
    } catch (Exception $e) {
        $statusCode = 500;
        $response = ["error" => "Error interno del servidor", "detalle" => $e->getMessage()];
    }

    http_response_code($statusCode);
    echo json_encode($response);
}
