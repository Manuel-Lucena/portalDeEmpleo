<?php

namespace Models;

class Solicitud
{
    private $id;
    private $idAlumno;
    private $idOferta;
    private $fechaSolicitud;
    private $estado;
    private $favorito;


    public function __construct($idAlumno, $idOferta, $fechaSolicitud, $estado, $id = null, $favorito = false)
    {
        $this->id = $id;
        $this->idAlumno = $idAlumno;
        $this->idOferta = $idOferta;
        $this->fechaSolicitud = $fechaSolicitud;
        $this->estado = $estado;
        $this->favorito = $favorito;
    }


    public function getId()
    {
        return $this->id;
    }

    public function getIdAlumno()
    {
        return $this->idAlumno;
    }

    public function getIdOferta()
    {
        return $this->idOferta;
    }

    public function getFechaSolicitud()
    {
        return $this->fechaSolicitud;
    }

    public function getEstado()
    {
        return $this->estado;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function setIdAlumno($idAlumno)
    {
        $this->idAlumno = $idAlumno;
    }

    public function setIdOferta($idOferta)
    {
        $this->idOferta = $idOferta;
    }

    public function setFechaSolicitud($fechaSolicitud)
    {
        $this->fechaSolicitud = $fechaSolicitud;
    }

    public function setEstado($estado)
    {
        $this->estado = $estado;
    }

    public function getFavorito()
    {
        return $this->favorito;
    }

    public function setFavorito($favorito)
    {
        $this->favorito = $favorito;
    }
}
