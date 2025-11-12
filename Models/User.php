<?php

namespace Models;

class User {
    private $id;
    private $nombreUsuario;
    private $password;
    private $idRol;

    public function __construct($nombreUsuario, $password, $idRol, $id = null) {
        $this->id = $id;
        $this->nombreUsuario = $nombreUsuario;
        $this->password = $password;
        $this->idRol = $idRol;
    }

    public function getId() {
        return $this->id;
    }

    public function getNombreUsuario() {
        return $this->nombreUsuario;
    }

    public function getPassword() {
        return $this->password;
    }

    public function getIdRol() {
        return $this->idRol;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function setNombreUsuario($nombreUsuario) {
        $this->nombreUsuario = $nombreUsuario;
    }
public function setPassword($password) {
    $this->password = $password; 
}


    public function setIdRol($idRol) {
        $this->idRol = $idRol;
    }
}
