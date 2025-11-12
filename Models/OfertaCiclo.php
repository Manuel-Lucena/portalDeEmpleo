<?php

namespace Models;

class OfertaCiclo
{
    private $id;
    private $idOferta;
    private $idCiclo;


    public function __construct($idOferta, $idCiclo, $id = null)
    {
        $this->idOferta = $idOferta;
        $this->idCiclo = $idCiclo;
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }
    public function setId($id)
    {
        $this->id = $id;
    }

    public function getIdOferta()
    {
        return $this->idOferta;
    }

    public function getIdCiclo()
    {
        return $this->idCiclo;
    }



    public function setIdOferta($idOferta)
    {
        $this->idOferta = $idOferta;
    }
}
