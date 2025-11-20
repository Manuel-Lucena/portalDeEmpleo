<?php
namespace Controllers;

use League\Plates\Engine;
use Repositorys\RepoEmpresa; 

class EliminarEmpresaController {
    private $templates;

    public function __construct() {
        $this->templates = new Engine(__DIR__ . '/../templates');
    }

    public function index() {
        $idEmpresa = $_POST['idEmpresa'] ?? null;

        if (!$idEmpresa) {
            header("Location: ../public/index.php?menu=PanelAdmin");
        }

        echo $this->templates->render('Empresa/EliminarEmpresa', [
            'idEmpresa' => $idEmpresa
        ]);
    }

  
    public function eliminarEmpresa() {
        $idEmpresa = $_POST['idEmpresa'] ?? null;

        if ($idEmpresa) {
            RepoEmpresa::delete($idEmpresa);
        }

        header("Location: ../public/index.php?menu=PanelAdmin");

    }
}
