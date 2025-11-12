<?php
namespace Controllers;

use Repositorys\RepoAlumno;
use Repositorys\RepoEmpresa;
use Repositorys\RepoEmpresaCandidata;
use Repositorys\RepoUser;
use League\Plates\Engine;

class PanelAdminController {
    private $templates;

    public function __construct() {
        $this->templates = new Engine(__DIR__ . '/../templates');
    }

    public function index() {
    $empresas = RepoEmpresa::findAll(); 
    $empresasC = RepoEmpresaCandidata::findAll();
    $usuarios = RepoUser::findAll(); 
    $data = [
        'titulo' => 'Panel de administración',
        'empresas' => $empresas,
        'empresasC' => $empresasC,
        'usuarios' => $usuarios
    ];
echo $this->templates->render('PanelAdmin', $data);

}

}
