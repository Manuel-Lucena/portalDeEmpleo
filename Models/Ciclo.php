<?php

namespace Models;

class Ciclo {
    private $id;
    private $nombre;
    private $tipo;
    private $idFamilia;

    public function __construct($nombre, $tipo, $idFamilia, $id = null) {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->tipo = $tipo;
        $this->idFamilia = $idFamilia;
    }

    public function getId() {
        return $this->id;
    }

    public function getNombre() {
        return $this->nombre;
    }

    public function getTipo() {
        return $this->tipo;
    }

    public function getIdFamilia() {
        return $this->idFamilia;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function setNombre($nombre) {
        $this->nombre = $nombre;
    }

    public function setTipo($tipo) {
        $this->tipo = $tipo;
    }

    public function setIdFamilia($idFamilia) {
        $this->idFamilia = $idFamilia;
    }
}
