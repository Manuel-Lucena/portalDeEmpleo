<?php
namespace Models;
    class Forgotten_password{

    private $id;
    private $idUser;
    private $token;

    public function __construct($idUser, $id = null, $token) {
        $this->id = $id;
        $this->idUser = $idUser;
        $this->token = $token;
    }

     public function getId() {
        return $this->id;
    }

    public function getIdUser() {
        return $this->idUser;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function setIdUser($idUser) {
        $this->idUser = $idUser;
    }


    public function getToken() {
        return this->token;
    }

    public function setToken($token){
        $this->token = $token;
    }
}


?>