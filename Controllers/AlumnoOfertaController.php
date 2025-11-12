<?php
namespace Controllers;

use League\Plates\Engine;
use Repositorys\RepoOferta;

class AlumnoOfertaController {
    private $templates;

    public function __construct() {
        $this->templates = new Engine(__DIR__ . '/../templates');
    }

    public function index() {
        $ofertas = RepoOferta::findAll() ?? []; 


        $data = [
            'titulo'   => 'Ofertas',
            'mensaje'  => '',
            'ofertas'  => $ofertas
        ];

        echo $this->templates->render('AlumnoOfertas', $data);
    }
}
