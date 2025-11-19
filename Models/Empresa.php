<?php

namespace Models;

class Empresa {
    private $id;           
    private $idUser;      
    private $nombreEmpresa;
    private $telefono;
    private $direccion;
    private $personaContacto;
    private $email;
    private $logo;

    public function __construct(
        $id = null,
        $idUser = null,
        $nombreEmpresa = '',
        $telefono = null,
        $direccion = null,
        $personaContacto = null,
        $email = '',
        $logo = null
    ) {
        $this->id = $id;
        $this->idUser = $idUser;
        $this->nombreEmpresa = $nombreEmpresa;
        $this->telefono = $telefono;
        $this->direccion = $direccion;
        $this->personaContacto = $personaContacto;
        $this->email = $email;
        $this->logo = $logo;
    }


    public function getId() {
        return $this->id;
    }

    public function getIdUser() {
        return $this->idUser;
    }

    public function getNombreEmpresa() {
        return $this->nombreEmpresa;
    }

    public function getTelefono() {
        return $this->telefono;
    }

    public function getDireccion() {
        return $this->direccion;
    }

    public function getPersonaContacto() {
        return $this->personaContacto;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getLogo() {
        return $this->logo;
    }


    public function setId($id) {
        $this->id = $id;
    }

    public function setIdUser($idUser) {
        $this->idUser = $idUser;
    }

    public function setNombreEmpresa($nombreEmpresa) {
        $this->nombreEmpresa = $nombreEmpresa;
    }

    public function setTelefono($telefono) {
        $this->telefono = $telefono;
    }

    public function setDireccion($direccion) {
        $this->direccion = $direccion;
    }

    public function setPersonaContacto($personaContacto) {
        $this->personaContacto = $personaContacto;
    }

    public function setEmail($email) {
        $this->email = $email;
    }

    public function setLogo($logo) {
        $this->logo = $logo;
    }
}
