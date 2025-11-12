<?php

namespace Controllers;


use League\Plates\Engine;
use Models\Oferta;
use Models\OfertaCiclo;
use Repositorys\RepoCiclo;
use Repositorys\RepoOferta;


class GestionOfertaController {
    private $templates;

    public function __construct() {
        $this->templates = new Engine(__DIR__ . '/../templates');
    }


    public function index($idEmpresa) {
        $ciclos = RepoCiclo::findAll();

    
        $data = [
            'titulo' => 'Crear Oferta',
            'idEmpresa' => $idEmpresa,
            'ciclo' => $ciclos,
        ];

        echo $this->templates->render('CrearOferta', $data);
    }



public function eliminar($idOferta) {

    $oferta = RepoOferta::findById($idOferta);
    //validar en el validator
    if ($oferta) {
    RepoOferta::delete($idOferta);
    }

    header("Location: index.php?menu=Oferta");
}




public function saveOferta() {
    $idEmpresa = $_POST['idEmpresa'];
    $titulo = $_POST['titulo'];
    $fechaInicio = $_POST['fechaInicio'];
    $fechaFin = $_POST['fechaFin'];
    $descripcion = $_POST['descripcion'];
    $idCiclo = $_POST['idCiclo'];

    $oferta = new Oferta($idEmpresa, $titulo, $fechaInicio, $fechaFin, $descripcion);
    
    $ofertaCiclo = new OfertaCiclo($oferta->getId(), $idCiclo);

    RepoOferta::save($oferta, $ofertaCiclo);




    header("Location: index.php?menu=Oferta");
}



}
