<?php

namespace Controllers;

use League\Plates\Engine;



class PoliticasController {
       private $templates;

    public function __construct() {
        $this->templates = new Engine(__DIR__ . '/../templates');
    }

    public function index() {
      
        $data = [
            'titulo' => 'POLÍTICAS DE PRIVACIDAD',
        ];

        echo $this->templates->render('Politicas', $data);
    }
}
