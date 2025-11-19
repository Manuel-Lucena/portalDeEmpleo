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


    $page = isset($_GET['page']) ? $_GET['page'] : 1;
    $size = isset($_GET['size']) ? $size = $_GET['size'] : 2;

    if ($page < 1) $page = 1;
    if ($size < 1) $size = 10;

    $offset = ($page - 1) * $size;

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['busqueda'])) {

        $texto = trim($_POST['busqueda']);

        
        $empresas = RepoEmpresa::findAllPaginatedByName($size, $offset, $texto);


        $totalEmpresas = RepoEmpresa::countByName($texto);
    } else {


        $empresas = RepoEmpresa::findAllPaginated($size, $offset);
        $totalEmpresas = RepoEmpresa::countAll();
    }

    $totalPages = ceil($totalEmpresas / $size);

    $empresasC = RepoEmpresaCandidata::findAll();
    $usuarios = RepoUser::findAll();

    $data = [
        'titulo' => 'Panel de administración',
        'empresas' => $empresas,
        'empresasC' => $empresasC,
        'usuarios' => $usuarios,
        'page' => $page,
        'size' => $size,
        'totalPages' => $totalPages
    ];

    echo $this->templates->render('PanelAdmin', $data);
}


}
