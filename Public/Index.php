<?php
require_once __DIR__ . "/../vendor/autoload.php";
include_once __DIR__ . "/../Downloads/mi_autoload.php";

use Helpers\Sesion;
use Helpers\Login;
use Services\PDFService;
use Controllers\LoginController;
use Controllers\RegistroEmpresaController;
use Controllers\InicioController;
use Controllers\SolicitudController;
use Controllers\OfertaController;
use Controllers\PanelAdminController;
use Controllers\EditarEmpresaController;
use Controllers\EliminarEmpresaController;
use Controllers\DetallesEmpresaController;
use Controllers\GestionOfertaController;
use Repositorys\RepoUser;
use Services\PDFServices;

Sesion::abrirsesion();




if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['accion'] ?? '') == 'Login') {
    $username = $_POST['username'];
    $password = $_POST['password'];


    $usuario = RepoUser::verificarUsuario($username, $password);
    if ($usuario) {
        Login::login($usuario);
        header("Location: Index.php?menu=Inicio");
        exit;
    }
}






if (!Login::estaLogeado()) {
    $menu = $_GET['menu'] ?? 'Login';

    if ($menu === 'RegistroEmpresa') {
        $controller = new RegistroEmpresaController();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller->procesarRegistroCandidata();
        } else {
            $controller->index();
        }
    } else {
        $controller = new LoginController();
        $controller->index();
    }
} else {

    $menu = $_GET['menu'] ?? 'Inicio';
    switch ($menu) {
        case 'Inicio':


            $controller = new InicioController();
            $controller->index();
            break;

        case 'GestionOferta':
            $idEmpresa = $_GET['idEmpresa'] ?? null;
            $accion = $_GET['accion'] ?? ($_POST['accion'] ?? null);
            $idOferta = $_POST['id'] ?? $_GET['id'] ?? null;

            $controller = new GestionOfertaController();

            if ($accion === 'GuardarOferta' && $_SERVER['REQUEST_METHOD'] === 'POST') {
                $controller->saveOferta();
            } elseif ($accion === 'Eliminar' && $idOferta) {
                $controller->eliminar($idOferta);
                header("Location: index.php?menu=Oferta");
                exit;
            } else {
                $controller->index($idEmpresa);
            }
            break;



        case 'Oferta':


            $controller = new OfertaController();
            $controller->index();
            break;
        case 'Solicitud':


            $controller = new SolicitudController();
            $controller->index();
            break;

        case 'GenerarPDFAlumnos':
    
            PDFServices::generarListadoAlumnos();
            break;

        case 'PanelAdmin':
            if(Login::getRol() == 1){
            $controller = new PanelAdminController();
            $controller->index();
            } else {
                 header("Location: Index.php");
            }
            break;


        case 'DetallesEmpresa':
            $controller = new DetallesEmpresaController();

            if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['accion'] ?? '') === 'detalles') {
                $controller->index($_POST['idEmpresa']);
            }
            break;


        case 'RegistroEmpresa':
            $controller = new RegistroEmpresaController();

            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion'])) {
                if ($_POST['accion'] === 'aprobar' || $_POST['accion'] === 'rechazar') {
                    $controller->procesarPostCandidata();
                } elseif ($_POST['accion'] === 'Registrar') {
                    $controller->procesarRegistro();
                }
            } else {
                $controller->index();
            }
            break;


        case 'EliminarEmpresa':
            $controller = new EliminarEmpresaController();

            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion'])) {
                if ($_POST['accion'] === 'confirmarEliminar') {
                    $controller->eliminarEmpresa();
                } else {
                    $controller->index();
                }
            }
            break;



        case 'EditarEmpresa':
            $controller = new EditarEmpresaController();
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['accion'] === 'GuardarCambios') {
                $controller->updateEmpresa();
            } else {
                $controller->index();
            }
            break;


        case 'Logout':
            Login::logout();
            header("Location: Index.php");
        default:
            break;
    }
}
