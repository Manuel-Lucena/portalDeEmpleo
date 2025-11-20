<?php

namespace Controllers;

use League\Plates\Engine;
use Models\Empresa;
use Models\User;
use Repositorys\RepoUser;
use Repositorys\RepoEmpresa;
use Repositorys\RepoEmpresaCandidata;
use Helpers\Login;

class RegistroEmpresaController
{
    private $templates;

    public function __construct()
    {
        $this->templates = new Engine(__DIR__ . '/../templates');
    }

    public function index()
    {

        $logueado = Login::estaLogeado();

        $data = [
            'titulo' => 'Registro',
            'logueado' => $logueado
        ];

        echo $this->templates->render('Empresa/RegistroEmpresa', $data);
    }



    public function procesarPostCandidata()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $idEmpresa = $_POST['idEmpresa'] ?? null;
            $accion = $_POST['accion'] ?? null;

            if ($idEmpresa && $accion) {
                if ($accion === 'aprobar') {
                    RepoEmpresaCandidata::aprobar($idEmpresa);
                } elseif ($accion === 'rechazar') {
                    RepoEmpresaCandidata::delete($idEmpresa);
                }
            }


            header("Location: ../public/index.php?menu=PanelAdmin");
        }
    }

    public function procesarRegistro()
    {


        $nombreEmpresa = $_POST['nombreEmpresa'] ?? '';
        $usuario = $_POST['nombreUsuario'] ?? '';
        $contrasena = $_POST['contrasena'] ?? '';
        $email = $_POST['email'] ?? '';
        $telefono = $_POST['telefono'] ?? '';
        $personaContacto = $_POST['personaContacto'] ?? '';
        $direccion = $_POST['direccion'] ?? '';
        $logo = $_FILES['logoEmpresa'] ?? null;

        $user = new User($usuario, password_hash($contrasena, PASSWORD_DEFAULT), 2);
        $empresa = new Empresa(null, null, $nombreEmpresa, $telefono, $direccion, $personaContacto, $email, null);

        RepoEmpresa::save($empresa, $user);

        if ($logo && $logo['tmp_name']) {
            $rutaLogo = "foto_" . $user->getId() . ".png";
            $destino = __DIR__ . '/../fotos/empresa/' . $rutaLogo;

            if (move_uploaded_file($logo['tmp_name'], $destino)) {
                $empresa->setLogo($rutaLogo);
                RepoEmpresa::updateLogo($user->getId(), $rutaLogo);
            }
        }

        header("Location: ../public/index.php?menu=PanelAdmin");

    }

    public function procesarRegistroCandidata()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') return;

        $nombreEmpresa   = $_POST['nombreEmpresa'] ?? '';
        $usuario         = $_POST['nombreUsuario'] ?? '';
        $contrasena      = $_POST['contrasena'] ?? '';
        $email           = $_POST['email'] ?? '';
        $telefono        = $_POST['telefono'] ?? '';
        $personaContacto = $_POST['personaContacto'] ?? '';
        $direccion       = $_POST['direccion'] ?? '';
        $logo            = $_FILES['logoEmpresa'] ?? null;


        $user = new User($usuario, password_hash($contrasena, PASSWORD_DEFAULT), 2);

        $empresaCandidata = new Empresa(
            null,
            null,
            $nombreEmpresa,
            $telefono,
            $direccion,
            $personaContacto,
            $email,
            null
        );


        RepoEmpresaCandidata::save($empresaCandidata, $user);


        if ($logo && $logo['tmp_name']) {
            $rutaLogo = "foto_" . $empresaCandidata->getIdUser() . ".png";
            $destino = __DIR__ . '/../fotos/empresa/' . $rutaLogo;

            if (move_uploaded_file($logo['tmp_name'], $destino)) {
                $empresaCandidata->setLogo($rutaLogo);
                RepoEmpresaCandidata::updateLogo($empresaCandidata->getIdUser(), $rutaLogo);
            }
        }

        header("Location: ../public/index.php?menu=Login");

    }
}
