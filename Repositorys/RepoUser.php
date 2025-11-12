<?php

namespace Repositorys;

use Models\User;

class RepoUser {

    public static function findById($id) {
        $con = DB::getConnection();
        $stmt = $con->prepare("SELECT * FROM USER WHERE id = ?");
        $stmt->execute([$id]);
        $fila = $stmt->fetch(\PDO::FETCH_ASSOC);

        if ($fila) {
            return new User(
                $fila['nombreUsuario'],
                $fila['password'],
                $fila['idRol'],
                $fila['id']
            );
        }

        return null;
    }

    public static function findAll() {
        $con = DB::getConnection();
        $stmt = $con->prepare("SELECT * FROM USER");
        $stmt->execute();
        $filas = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $usuarios = [];
        foreach ($filas as $fila) {
            $usuarios[] = new User(
                $fila['nombreUsuario'],
                $fila['password'],
                $fila['idRol'],
                $fila['id']
            );
        }

        return $usuarios;
    }

  public static function save($user) {
    $con = DB::getConnection();
    $stmt = $con->prepare("INSERT INTO USER (nombreUsuario, password, idRol) VALUES (?, ?, ?)");
    $stmt->execute([
        $user->getNombreUsuario(),
        $user->getPassword(),
        $user->getIdRol()
    ]);


    $user->setId($con->lastInsertId());
}


    public static function update($user) {
        $con = DB::getConnection();
        $stmt = $con->prepare("UPDATE USER SET nombreUsuario = ?, password = ?, idRol = ? WHERE id = ?");
        $stmt->execute([
            $user->getNombreUsuario(),
            $user->getPassword(),
            $user->getIdRol(),
            $user->getId()
        ]);
    }

    public static function delete($id) {
        $con = DB::getConnection();
        $stmt = $con->prepare("DELETE FROM USER WHERE id = ?");
        $stmt->execute([$id]);
    }

  public static function verificarUsuario($nombreUsuario, $password) {
    $con = DB::getConnection();
    $stmt = $con->prepare("SELECT * FROM USER WHERE nombreUsuario = ?");
    $stmt->execute([$nombreUsuario]);
    $fila = $stmt->fetch(\PDO::FETCH_ASSOC);

    if ($fila) {

        if (password_verify($password, $fila['password'])) {
            return $fila; 
        } else {
            return false; // Contraseña incorrecta
        }
    }
    return false; // Usuario no existe
}

}
?>
