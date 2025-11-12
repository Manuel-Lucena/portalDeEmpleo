<?php

namespace Models;

class Estudio
{
    private $id;
    private $idAlumno;
    private $idCiclo;
    private $fechaInicio;
    private $fechaFin;

    public function __construct($idAlumno, $idCiclo, $fechaInicio, $fechaFin, $id = null)
    {
        $this->id = $id;
        $this->idAlumno = $idAlumno;
        $this->idCiclo = $idCiclo;
        $this->fechaInicio = $fechaInicio;
        $this->fechaFin = $fechaFin;
    }

    public function getId()
    {
        return $this->id;
    }
    public function getIdAlumno()
    {
        return $this->idAlumno;
    }
    public function getIdCiclo()
    {
        return $this->idCiclo;
    }
    public function getFechaInicio()
    {
        return $this->fechaInicio;
    }
    public function getFechaFin()
    {
        return $this->fechaFin;
    }

    public function setId($id)
    {
        $this->id = $id;
    }
    public function setIdAlumno($idAlumno)
    {
        $this->idAlumno = $idAlumno;
    }
    public function setIdCiclo($idCiclo)
    {
        $this->idCiclo = $idCiclo;
    }
    public function setFechaInicio($fechaInicio)
    {
        $this->fechaInicio = $fechaInicio;
    }
    public function setFechaFin($fechaFin)
    {
        $this->fechaFin = $fechaFin;
    }
}
