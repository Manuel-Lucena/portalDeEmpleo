<?php

namespace Repositorys;
use Models\Ciclo;

require_once __DIR__ . '/../Models/Ciclo.php';
require_once 'DB.php';

class RepoCiclo {

    public static function findById($id) {
        $con = DB::getConnection();
        $stmt = $con->prepare("SELECT * FROM CICLO WHERE id = ?");
        $stmt->execute([$id]);
        $fila = $stmt->fetch(\PDO::FETCH_ASSOC);

        if ($fila) {
            return new Ciclo(
                $fila['nombre'],
                $fila['tipo'],
                $fila['idFamilia'],
                $fila['id']
            );
        }

        return null;
    }


    public static function findByFamilia($idFamilia) {
    $con = DB::getConnection();
    $stmt = $con->prepare("SELECT * FROM CICLO WHERE idFamilia = ?");
    $stmt->execute([$idFamilia]);
    $filas = $stmt->fetchAll(\PDO::FETCH_ASSOC);

    $ciclos = [];
    foreach ($filas as $fila) {
        $ciclos[] = new \Models\Ciclo(
            $fila['nombre'],
            $fila['tipo'],
            $fila['idFamilia'],
            $fila['id']
        );
    }

    return $ciclos;
}


    public static function findAll() {
        $con = DB::getConnection();
        $stmt = $con->prepare("SELECT * FROM CICLO");
        $stmt->execute();
        $filas = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $ciclos = [];
        foreach ($filas as $fila) {
            $ciclos[] = new Ciclo(
                $fila['nombre'],
                $fila['tipo'],
                $fila['idFamilia'],
                $fila['id']
            );
        }

        return $ciclos;
    }

    public static function save($ciclo) {
        $con = DB::getConnection();
        $stmt = $con->prepare("INSERT INTO CICLO (nombre, tipo, idFamilia) VALUES (?, ?, ?)");
        $stmt->execute([
            $ciclo->getNombre(),
            $ciclo->getTipo(),
            $ciclo->getIdFamilia()
        ]);
        $ciclo->setId($con->lastInsertId());
    }

    public static function update($ciclo) {
        $con = DB::getConnection();
        $stmt = $con->prepare("UPDATE CICLO SET nombre = ?, tipo = ?, idFamilia = ? WHERE id = ?");
        $stmt->execute([
            $ciclo->getNombre(),
            $ciclo->getTipo(),
            $ciclo->getIdFamilia(),
            $ciclo->getId()
        ]);
    }

    public static function delete($id) {
        $con = DB::getConnection();
        $stmt = $con->prepare("DELETE FROM CICLO WHERE id = ?");
        $stmt->execute([$id]);
    }
}
?>
