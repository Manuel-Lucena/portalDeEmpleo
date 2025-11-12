<?php

namespace Repositorys;
use Models\Estudio;

include_once __DIR__ . "/../Downloads/mi_autoload.php"; 

class RepoEstudios {

    public static function findById($id) {
        $con = DB::getConnection();
        $stmt = $con->prepare("SELECT * FROM ESTUDIOS WHERE id = ?");
        $stmt->execute([$id]);
        $fila = $stmt->fetch(\PDO::FETCH_ASSOC);

        if ($fila) {
            return new Estudio(
                $fila['idAlumno'],
                $fila['idCiclo'],
                $fila['fechaInicio'],
                $fila['fechaFin'],
                $fila['id']
            );
        }

        return null;
    }

    public static function findAll() {
        $con = DB::getConnection();
        $stmt = $con->prepare("SELECT * FROM ESTUDIOS");
        $stmt->execute();
        $filas = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $estudios = [];
        foreach ($filas as $fila) {
            $estudios[] = new Estudio(
                $fila['idAlumno'],
                $fila['idCiclo'],
                $fila['fechaInicio'],
                $fila['fechaFin'],
                $fila['id']
            );
        }

        return $estudios;
    }

    public static function save($estudio) {
        $con = DB::getConnection();
        $stmt = $con->prepare("INSERT INTO ESTUDIOS (idAlumno, idCiclo, fechaInicio, fechaFin) VALUES (?, ?, ?, ?)");
        $stmt->execute([
            $estudio->getIdAlumno(),
            $estudio->getIdCiclo(),
            $estudio->getFechaInicio(),
            $estudio->getFechaFin()
        ]);
        $estudio->setId($con->lastInsertId());
    }

    public static function update($estudio) {
        $con = DB::getConnection();
        $stmt = $con->prepare("UPDATE ESTUDIOS SET idAlumno = ?, idCiclo = ?, fechaInicio = ?, fechaFin = ? WHERE id = ?");
        $stmt->execute([
            $estudio->getIdAlumno(),
            $estudio->getIdCiclo(),
            $estudio->getFechaInicio(),
            $estudio->getFechaFin(),
            $estudio->getId()
        ]);
    }

    public static function delete($id) {
        $con = DB::getConnection();
        $stmt = $con->prepare("DELETE FROM ESTUDIOS WHERE id = ?");
        $stmt->execute([$id]);
    }
}
?>
