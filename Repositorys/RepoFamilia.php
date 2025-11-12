<?php

namespace Repositorys;

use Models\Familia;

class RepoFamilia {

    public static function findById($id) {
        $con = DB::getConnection();
        $stmt = $con->prepare("SELECT * FROM FAMILIA WHERE id = ?");
        $stmt->execute([$id]);
        $fila = $stmt->fetch(\PDO::FETCH_ASSOC);

        if ($fila) {
            return new Familia($fila['nombre'], $fila['id']);
        }

        return null;
    }

    public static function findAll() {
        $con = DB::getConnection();
        $stmt = $con->prepare("SELECT * FROM FAMILIA");
        $stmt->execute();
        $filas = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $familias = [];
        foreach ($filas as $fila) {
            $familias[] = new Familia($fila['nombre'], $fila['id']);
        }

        return $familias;
    }

    public static function save($familia) {
        $con = DB::getConnection();
        $stmt = $con->prepare("INSERT INTO FAMILIA (nombre) VALUES (?)");
        $stmt->execute([$familia->getNombre()]);
        $familia->setId($con->lastInsertId());
    }

    public static function update($familia) {
        $con = DB::getConnection();
        $stmt = $con->prepare("UPDATE FAMILIA SET nombre = ? WHERE id = ?");
        $stmt->execute([$familia->getNombre(), $familia->getId()]);
    }

    public static function delete($id) {
        $con = DB::getConnection();
        $stmt = $con->prepare("DELETE FROM FAMILIA WHERE id = ?");
        $stmt->execute([$id]);
    }
}
?>
