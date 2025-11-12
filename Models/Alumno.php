<?php
namespace Models;

class Alumno {
    private $id;
    private $idUser;
    private $nombre;
    private $fechaNacimiento;
    private $direccion;
    private $telefono;
    private $curriculum;
    private $email;
    private $foto;

  public function __construct($id = null, $idUser = null, $nombre = null, $email = null, $fechaNacimiento = null, $direccion = null, $telefono = null, $curriculum = null, $foto = null) {
        $this->id = $id;
        $this->idUser = $idUser;
        $this->nombre = $nombre;
        $this->email = $email;
        $this->fechaNacimiento = $fechaNacimiento;
        $this->direccion = $direccion;
        $this->telefono = $telefono;
        $this->curriculum = $curriculum;
        $this->foto = $foto;
    }
    public function getEmail() {
        return $this->email;
    }

public function setEmail($email) {
    $this->email = $email;
}

    public function getId() {
        return $this->id;
    }

    public function getIdUser() {
        return $this->idUser;
    }

    public function getNombre() {
        return $this->nombre;
    }

    public function getFechaNacimiento() {
        return $this->fechaNacimiento;
    }

    public function getDireccion() {
        return $this->direccion;
    }

    public function getTelefono() {
        return $this->telefono;
    }

    public function getCurriculum() {
        return $this->curriculum;
    }

    

    public function setId($id) {
        $this->id = $id;
    }

    public function setIdUser($idUser) {
        $this->idUser = $idUser;
    }

    public function setNombre($nombre) {
        $this->nombre = $nombre;
    }


    public function getFoto() { 
        return $this->foto; 
    }


    public function setFechaNacimiento($fechaNacimiento) {
        $this->fechaNacimiento = $fechaNacimiento;
    }

    public function setDireccion($direccion) {
        $this->direccion = $direccion;
    }

    public function setTelefono($telefono) {
        $this->telefono = $telefono;
    }

    public function setCurriculum($curriculum) {
        $this->curriculum = $curriculum;
    }


    public function setFoto($foto) {
        $this->foto = $foto; 
    }
}
