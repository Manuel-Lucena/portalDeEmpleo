<?php

namespace Controllers;

use League\Plates\Engine;
use Repositorys\RepoEmpresa;
use Models\Empresa;

class DetallesEmpresaController {
       private $templates;

    public function __construct() {
        $this->templates = new Engine(__DIR__ . '/../templates');
    }

    public function index($id) {
        $empresa = RepoEmpresa::findById($id); 
        $data = [
            'titulo' => 'Detalles de la Empresa',
            'empresa' => $empresa
        ];

        echo $this->templates->render('DetallesEmpresa', $data);
    }
}
