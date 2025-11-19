<?php

namespace Controllers;

use League\Plates\Engine;


class SolicitudController
{
    private $templates;

    public function __construct()
    {
        $this->templates = new Engine(__DIR__ . '/../templates');
    }

    public function index()
    {
    
        $data = [
            'titulo'      => 'Solicitudes de empleo',
            'mensaje'     => 'Estas son las solicitudes relacionadas',
        ];

        echo $this->templates->render('Solicitudes', $data);
    }
}
