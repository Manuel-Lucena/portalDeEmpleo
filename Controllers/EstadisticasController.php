<?php

namespace Controllers;
include_once "../Downloads/mi_autoload.php";
use League\Plates\Engine;

class EstadisticasController {
    private $templates;

    public function __construct() {
        $this->templates = new Engine(__DIR__ . '/../templates');
    }

    public function index() {
        $data = [
          'titulo' => 'Estadisticas'
        ];

        echo $this->templates->render('Empresa/Estadisticas', $data);
    }
}
