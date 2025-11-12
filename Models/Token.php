<?php
namespace Models;

class Token {
    private $id;
    private $idUser;
    private $token;

    public function __construct($id = null, $idUser = null, $token = null) {
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

    public function getToken() {
        return $this->token;
    }


    public function setId($id) {
        $this->id = $id;
    }

    public function setIdUser($idUser) {
        $this->idUser = $idUser;
    }

    public function setToken($token) {
        $this->token = $token;
    }
}
?>
