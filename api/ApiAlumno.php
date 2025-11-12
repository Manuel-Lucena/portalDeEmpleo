<?php
header('Content-Type: application/json');
require_once '../Downloads/mi_autoload.php';

use Models\Alumno;
use Models\User;
use Repositorys\RepoAlumno;
use Models\Estudio;
use Repositorys\RepoUser;
use Repositorys\RepoEstudios;

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
    try {
        // --- Buscar por ID ---
        if (isset($_GET['id'])) {
            $alumno = RepoAlumno::findById($_GET['id']);
            if ($alumno) {
                echo json_encode([
                    'id' => $alumno->getId(),
                    'nombre' => $alumno->getNombre(),
                    'email' => $alumno->getEmail(),
                    'fecha_nacimiento' => $alumno->getFechaNacimiento(),
                    'direccion' => $alumno->getDireccion(),
                    'telefono' => $alumno->getTelefono(),
                    'foto' => $alumno->getFoto()
                ]);
            } else {
                http_response_code(404);
                echo json_encode(["error" => "Alumno no encontrado"]);
            }

            // --- Buscar por nombre ---
        } elseif (isset($_GET['nombre'])) {
            $nombre = $_GET['nombre'];
            $alumnos = RepoAlumno::findByNombre($nombre);
            $alumnosArray = array_map(function ($alumno) {
                return [
                    'id' => $alumno->getId(),
                    'nombre' => $alumno->getNombre(),
                    'email' => $alumno->getEmail(),
                    'fecha_nacimiento' => $alumno->getFechaNacimiento(),
                    'direccion' => $alumno->getDireccion(),
                    'telefono' => $alumno->getTelefono()
                ];
            }, $alumnos);
            echo json_encode($alumnosArray);

            // --- Ordenar por nombre ---
        } elseif (isset($_GET['orden']) && $_GET['orden'] === 'nombre') {
            $alumnos = RepoAlumno::findAllOrderByNombre();
            $alumnosArray = array_map(function ($alumno) {
                return [
                    'id' => $alumno->getId(),
                    'nombre' => $alumno->getNombre(),
                    'email' => $alumno->getEmail(),
                    'fecha_nacimiento' => $alumno->getFechaNacimiento(),
                    'direccion' => $alumno->getDireccion(),
                    'telefono' => $alumno->getTelefono()
                ];
            }, $alumnos);
            echo json_encode($alumnosArray);

            // --- Todos los alumnos ---
        } else {
            $alumnos = RepoAlumno::findAll();
            $alumnosArray = array_map(function ($alumno) {
                return [
                    'id' => $alumno->getId(),
                    'nombre' => $alumno->getNombre(),
                    'email' => $alumno->getEmail(),
                    'fecha_nacimiento' => $alumno->getFechaNacimiento(),
                    'direccion' => $alumno->getDireccion(),
                    'telefono' => $alumno->getTelefono()
                ];
            }, $alumnos);
            echo json_encode($alumnosArray);
        }
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(["error" => "Error al obtener alumnos", "detalle" => $e->getMessage()]);
    }
}



function postAlumno()
{
    try {

        $nombre = $_POST['nombre'] ?? null;
        $email = $_POST['email'] ?? null;
        $password = $_POST['password'] ?? null;
        $fecha_nacimiento = $_POST['fecha_nacimiento'] ?? null;
        $direccion = $_POST['direccion'] ?? null;
        $telefono = $_POST['telefono'] ?? null;

        $curriculum = $_FILES['curriculum']['tmp_name'] ?? null;
        $fotoPerfil = $_FILES['fotoPerfil'] ?? null;

        $idCiclo = $_POST['idCiclo'] ?? null;
        $fechaInicio = $_POST['fechaInicio'] ?? null;
        $fechaFin = $_POST['fechaFin'] ?? null;


        if (!$nombre || !$email || !$password) {
            http_response_code(400);
            echo json_encode(["error" => "Faltan datos obligatorios"]);
            return;
        }

        $curriculumData = $curriculum ? file_get_contents($curriculum) : null;


        $user = new User($email, password_hash($password, PASSWORD_DEFAULT), 3);
        RepoUser::save($user);

        $alumno = new Alumno(
            null,
            $user->getId(),
            $nombre,
            $email,
            $fecha_nacimiento,
            $direccion,
            $telefono,
            $curriculumData,
            null
        );
        RepoAlumno::save($alumno);


        if ($idCiclo) {
            $estudio = new Estudio(
                $alumno->getId(),
                $idCiclo,
                $fechaInicio,
                $fechaFin
            );
            RepoEstudios::save($estudio);
        }


        if ($fotoPerfil && $fotoPerfil['tmp_name']) {
            $rutaFoto = "foto_" . $alumno->getIdUser() . ".png";
            move_uploaded_file($fotoPerfil['tmp_name'], "../fotos/alumno/" . $rutaFoto);
            $alumno->setFoto($rutaFoto);
            RepoAlumno::update($alumno);
        }


        echo json_encode([
            "mensaje" => "Alumno creado correctamente",
            "id" => $alumno->getId(),
            "foto" => $alumno->getFoto() ?? null
        ]);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode([
            "error" => "No se pudo crear el alumno",
            "detalle" => $e->getMessage()
        ]);
    }
}


function postAlumnosMasivo()
{
    try {
        $input = json_decode(file_get_contents('php://input'), true);

        $cicloId = $input['cicloId'] ?? null;
        $alumnos = $input['alumnos'] ?? [];

        if (!$cicloId || empty($alumnos)) {
            http_response_code(400);
            echo json_encode(["error" => "Faltan datos o alumnos para cargar"]);
            return;
        }

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

                $alumno = new Alumno(
                    null,
                    $user->getId(),
                    $nombre,
                    $email,
                    $fecha_nacimiento,
                    null,
                    null,
                    null,
                    null
                );
                RepoAlumno::save($alumno);

                if ($cicloId) {
                    $estudio = new Estudio($alumno->getId(), $cicloId, null, null);
                    RepoEstudios::save($estudio);
                }

                $insertados++;
            } catch (Exception $e) {
                $errores[] = "Fila $index: " . $e->getMessage();
            }
        }

        echo json_encode([
            "success" => true,
            "insertados" => $insertados,
            "errores" => $errores
        ]);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode([
            "error" => "Error al procesar la carga masiva",
            "detalle" => $e->getMessage()
        ]);
    }
}



function putAlumno()
{
    try {
        $body = file_get_contents('php://input');
        $data = json_decode($body, true);

        $id = $data['id'] ?? null;
        $nombre = $data['nombre'] ?? null;
        $email = $data['email'] ?? null;
        $fecha_nacimiento = $data['fecha_nacimiento'] ?? null;
        $direccion = $data['direccion'] ?? null;
        $telefono = $data['telefono'] ?? null;

        if (!$id || !$nombre || !$email) {
            http_response_code(400);
            echo json_encode(["error" => "Faltan datos obligatorios"]);
            return;
        }

        $alumno = RepoAlumno::findById($id);
        if (!$alumno) {
            http_response_code(404);
            echo json_encode(["error" => "Alumno no encontrado"]);
            return;
        }

        // Actualizamos campos básicos
        $alumno->setNombre($nombre);
        $alumno->setEmail($email);
        if (!empty($fecha_nacimiento)) {
            $alumno->setFechaNacimiento($fecha_nacimiento);
        } else {
            $alumno->setFechaNacimiento(null); 
        }
        $alumno->setDireccion($direccion);
        $alumno->setTelefono($telefono);

        // Actualizar currículum si viene Base64
        if (!empty($data['curriculum'])) {
            $alumno->setCurriculum(base64_decode($data['curriculum']));
        }

        // Actualizar foto si viene Base64
        if (!empty($data['fotoPerfil'])) {
            $fotoBinaria = base64_decode($data['fotoPerfil']);
            $rutaFoto = "foto_" . $alumno->getIdUser() . ".png";
            
            file_put_contents("../fotos/alumno/" . $rutaFoto, $fotoBinaria);
            $alumno->setFoto($rutaFoto);
        }

        RepoAlumno::update($alumno);

        echo json_encode(["success" => true, "mensaje" => "Alumno actualizado correctamente"]);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode([
            "error" => "No se pudo actualizar el alumno",
            "detalle" => $e->getMessage()
        ]);
    }
}

function deleteAlumno()
{
    try {
        $body = file_get_contents('php://input');
        $data = json_decode($body, true);

        $id = $data['id'] ?? null;
        if (!$id) {
            http_response_code(400);
            echo json_encode(["error" => "Falta el ID del alumno"]);
            return;
        }

        $resultado = RepoAlumno::delete($id);
        if ($resultado) {
            echo json_encode(["mensaje" => "Alumno eliminado correctamente"]);
        } else {
            http_response_code(404);
            echo json_encode(["error" => "Alumno no encontrado o no eliminado"]);
        }
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode([
            "error" => "Error interno del servidor",
            "detalle" => $e->getMessage()
        ]);
    }
} // <-- cierre deleteAlumno
