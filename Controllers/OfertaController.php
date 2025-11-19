<?php

namespace Controllers;

use League\Plates\Engine;
use Repositorys\RepoOferta;
use Repositorys\RepoEmpresa;
use Helpers\Login;

class OfertaController
{
    private $templates;

    public function __construct()
    {
        $this->templates = new Engine(__DIR__ . '/../templates');
    }
   public function index() {
    $idUser = Login::getId();
    $rol = Login::getRol();

    $idEmpresa = null; 

    if ($rol == 3) { 
        $ofertas = RepoOferta::findByAlumno($idUser);
    } elseif ($rol == 2) { 
        $empresa = RepoEmpresa::findByUserId($idUser);
        $ofertas = RepoOferta::findByEmpresa($empresa->getId());
        $idEmpresa = $empresa->getId();
    } else { 
        $ofertas = RepoOferta::findAll();
    }

    $data = [
        'titulo'    => 'Ofertas',
        'mensaje'   => '',
        'ofertas'   => $ofertas,
        'idEmpresa' => $idEmpresa
    ];

    echo $this->templates->render('Ofertas', $data);
}
}
