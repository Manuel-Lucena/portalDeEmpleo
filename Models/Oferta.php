<?php
namespace Models;
class Oferta {
    private $id;
    private $idEmpresa;
    private $titulo;
    private $descripcion;
    private $fechaInicio;
    private $fechaFin;

    public function __construct($idEmpresa, $titulo, $fechaInicio, $fechaFin, $descripcion, $id = null) {
        $this->id = $id;
        $this->idEmpresa = $idEmpresa;
        $this->titulo = $titulo;
        $this->descripcion = $descripcion;
        $this->fechaInicio = $fechaInicio;
        $this->fechaFin = $fechaFin;
    }

    public function getId() {
        return $this->id;
    }

    public function getIdEmpresa() {
        return $this->idEmpresa;
    }

    public function getTitulo() {
        return $this->titulo;
    }

    public function getDescripcion() {
        return $this->descripcion;
    }

    public function getFechaInicio() {
        return $this->fechaInicio;
    }

    public function getFechaFin() {
        return $this->fechaFin;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function setIdEmpresa($idEmpresa) {
        $this->idEmpresa = $idEmpresa;
    }

    public function setTitulo($titulo) {
        $this->titulo = $titulo;
    }

    public function setDescripcion($descripcion) {
        $this->descripcion = $descripcion;
    }

    public function setFechaInicio($fechaInicio) {
        $this->fechaInicio = $fechaInicio;
    }

    public function setFechaFin($fechaFin) {
        $this->fechaFin = $fechaFin;
    }
}
