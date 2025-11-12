<?php
namespace Controllers;

use League\Plates\Engine;
use Repositorys\RepoEmpresa; 
use Models\Empresa;
use Helpers\Login;

class EditarEmpresaController {
    private $templates;

    public function __construct() {
        $this->templates = new Engine(__DIR__ . '/../templates');
    }

    public function index() {
        $empresa = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['idEmpresa'])) {
            $id = $_POST['idEmpresa'];
            $empresa = RepoEmpresa::findById($id); 
        }

        $data = [
            'titulo' => 'Editar empresa',
            'empresa' => $empresa
        ];

        echo $this->templates->render('EditarEmpresa', $data);
    }


 public function updateEmpresa() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        $idUser = $_POST['idUser'];
        $nombreEmpresa = $_POST['nombreEmpresa'];
        $telefono = $_POST['telefono'];
        $direccion = $_POST['direccion'];
        $email = $_POST['email'];
        $contrasena = $_POST['contraseña'];
        $personaContacto = $_POST['personaContacto'];
        $logo = null;


        if (isset($_FILES['logo']) && $_FILES['logo']['tmp_name'] != '') {
            $rutaLogo = "logo_" . $idUser . ".png"; 
            move_uploaded_file($_FILES['logo']['tmp_name'], "../.fotos/empresa/" . $rutaLogo);
            $logo = $rutaLogo;
        }




    $empresa = new Empresa($idUser, $nombreEmpresa, $telefono, $direccion, $personaContacto, $email, $logo);


      
        RepoEmpresa::update($empresa);

        header("Location: index.php?menu=PanelAdmin");
        exit;
    }
}




}
