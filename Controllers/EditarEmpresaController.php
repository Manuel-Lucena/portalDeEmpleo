<?php

namespace Controllers;

use League\Plates\Engine;
use Repositorys\RepoEmpresa;
use Models\Empresa;
use Helpers\Login;

class EditarEmpresaController
{
    private $templates;

    public function __construct()
    {
        $this->templates = new Engine(__DIR__ . '/../templates');
    }

    public function index()
    {
        $empresa = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['idEmpresa'])) {
            $id = $_POST['idEmpresa'];
            $empresa = RepoEmpresa::findByUserId($id);
        }

        $data = [
            'titulo' => 'Editar empresa',
            'empresa' => $empresa
        ];

        echo $this->templates->render('Empresa/EditarEmpresa', $data);
    }


    public function updateEmpresa()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $idUser = (int) $_POST['idUser'];
            $nombreEmpresa = $_POST['nombreEmpresa'];
            $telefono = $_POST['telefono'] ?? null;
            $direccion = $_POST['direccion'] ?? null;
            $personaContacto = $_POST['personaContacto'] ?? null;
            $email = $_POST['email'];
            $logo = null;

      
            if (isset($_FILES['logo']) && $_FILES['logo']['tmp_name'] != '') {
                $rutaLogo = "logo_" . $idUser . ".png";
                $destino = __DIR__ . '/../fotos/empresa/' . $rutaLogo;
                move_uploaded_file($_FILES['logo']['tmp_name'], $destino);
                $logo = $rutaLogo;
            }


            $empresa = new Empresa(null, $idUser, $nombreEmpresa, $telefono, $direccion, $personaContacto, $email, $logo
            );

          
            RepoEmpresa::update($empresa);

            header("Location: index.php?menu=PanelAdmin");
        }
    }
}
