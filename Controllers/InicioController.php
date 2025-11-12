<?php
namespace Controllers;


use League\Plates\Engine;

class InicioController {
    private $templates;

    public function __construct() {
        $this->templates = new Engine(__DIR__ . '/../templates');
    }

    public function index() {
        $data = [
            'titulo' => 'Portal de Empleo',
            'mensaje' => 'Bienvenido al portal de empleo para alumnos y empresas'
        ];

        echo $this->templates->render('Inicio', $data);
    }
}
