<?php

namespace Controllers;

use League\Plates\Engine;
use Repositorys\RepoSolicitud;

class AlumnoSolicitudController {
       private $templates;

    public function __construct() {
        $this->templates = new Engine(__DIR__ . '/../templates');
    }

    public function index() {

        $solicitudes = RepoSolicitud::findAll(); 
        
        $data = [
            'titulo' => 'Solicitudes de empleo',
            'mensaje' => 'Estas son las solicitudes de su oferta',
            'solicitudes' => $solicitudes
        ];

        echo $this->templates->render('AlumnoSolicitudes', $data);
    }
}
