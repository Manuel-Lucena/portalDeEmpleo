<?php

namespace Repositorys;

use Models\Solicitud;

class RepoSolicitud {

    public static function findById($id) {
        $con = DB::getConnection();
        $stmt = $con->prepare("SELECT * FROM SOLICITUD WHERE id = ?");
        $stmt->execute([$id]);
        $fila = $stmt->fetch(\PDO::FETCH_ASSOC);

        if ($fila) {
            return new Solicitud(
                $fila['idAlumno'],
                $fila['idOferta'],
                $fila['fechaSolicitud'],
                $fila['estado'],
                $fila['id']
            );
        }

        return null;
    }

    public static function findAll() {
        $con = DB::getConnection();
        $stmt = $con->prepare("SELECT * FROM SOLICITUD");
        $stmt->execute();
        $filas = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $solicitudes = [];
        foreach ($filas as $fila) {
            $solicitudes[] = new Solicitud(
                $fila['id'],
                $fila['idAlumno'],
                $fila['fechaSolicitud'],
                $fila['estado']
             
            );
        }

        return $solicitudes;
    }

    public static function save($solicitud) {
        $con = DB::getConnection();
        $stmt = $con->prepare("INSERT INTO SOLICITUD (idAlumno, idOferta, fechaSolicitud, estado) VALUES (?, ?, ?, ?)");
        $stmt->execute([
            $solicitud->getIdAlumno(),
            $solicitud->getIdOferta(),
            $solicitud->getFechaSolicitud(),
            $solicitud->getEstado()
        ]);
        $solicitud->setId($con->lastInsertId());
    }

    public static function update($solicitud) {
        $con = DB::getConnection();
        $stmt = $con->prepare("UPDATE SOLICITUD SET idAlumno = ?, idOferta = ?, fechaSolicitud = ?, estado = ? WHERE id = ?");
        $stmt->execute([
            $solicitud->getIdAlumno(),
            $solicitud->getIdOferta(),
            $solicitud->getFechaSolicitud(),
            $solicitud->getEstado(),
            $solicitud->getId()
        ]);
    }

    public static function delete($id) {
        $con = DB::getConnection();
        $stmt = $con->prepare("DELETE FROM SOLICITUD WHERE id = ?");
        $stmt->execute([$id]);
    }
}
?>
