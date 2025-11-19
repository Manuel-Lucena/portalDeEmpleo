<?php

namespace Repositorys;

use Models\Solicitud;
use Repositorys\DB;

class RepoSolicitud
{

    public static function findById($id)
    {
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
                $fila['id'],
                $fila['favorito'] 
            );
        }

        return null;
    }


    public static function findByEmpresa($idEmpresa)
    {
        $con = DB::getConnection();
        $stmt = $con->prepare("
            SELECT s.* 
            FROM SOLICITUD s
            INNER JOIN OFERTA o ON s.idOferta = o.id
            WHERE o.idEmpresa = ?
        ");
        $stmt->execute([$idEmpresa]);
        $filas = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $solicitudes = [];
        foreach ($filas as $fila) {
            $solicitudes[] = new Solicitud(
                $fila['idAlumno'],
                $fila['idOferta'],
                $fila['fechaSolicitud'],
                $fila['estado'],
                $fila['id'],
                $fila['favorito'] 
            );
        }

        return $solicitudes;
    }


    public static function findAll()
    {
        $con = DB::getConnection();
        $stmt = $con->prepare("SELECT * FROM SOLICITUD");
        $stmt->execute();
        $filas = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $solicitudes = [];
        foreach ($filas as $fila) {
            $solicitudes[] = new Solicitud(
                $fila['idAlumno'],
                $fila['idOferta'],
                $fila['fechaSolicitud'],
                $fila['estado'],
                $fila['id'],
                $fila['favorito'] 
            );
        }

        return $solicitudes;
    }


    public static function findByAlumno($idAlumno)
    {
        $con = DB::getConnection();
        $stmt = $con->prepare("SELECT * FROM SOLICITUD WHERE idAlumno = ?");
        $stmt->execute([$idAlumno]);
        $filas = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $solicitudes = [];
        foreach ($filas as $fila) {
            $solicitudes[] = new Solicitud(
                $fila['idAlumno'],
                $fila['idOferta'],
                $fila['fechaSolicitud'],
                $fila['estado'],
                $fila['id'],
                $fila['favorito'] 
            );
        }

        return $solicitudes;
    }


    public static function findByOferta($idOferta)
    {
        $con = DB::getConnection();
        $stmt = $con->prepare("SELECT * FROM SOLICITUD WHERE idOferta = ?");
        $stmt->execute([$idOferta]);
        $filas = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $solicitudes = [];
        foreach ($filas as $fila) {
            $solicitudes[] = new Solicitud(
                $fila['idAlumno'],
                $fila['idOferta'],
                $fila['fechaSolicitud'],
                $fila['estado'],
                $fila['id'],
                $fila['favorito'] 
            );
        }

        return $solicitudes;
    }


    public static function save($solicitud)
    {
        $con = DB::getConnection();
        $stmt = $con->prepare(
            "INSERT INTO SOLICITUD (idAlumno, idOferta, fechaSolicitud, estado, favorito) 
            VALUES (?, ?, ?, ?, ?)"
        );
        $stmt->execute([
            $solicitud->getIdAlumno(),
            $solicitud->getIdOferta(),
            $solicitud->getFechaSolicitud(),
            $solicitud->getEstado(),
            $solicitud->getFavorito() ?? 0
        ]);

        $solicitud->setId($con->lastInsertId());
    }


    public static function update($solicitud)
    {
        $con = DB::getConnection();
        $stmt = $con->prepare(
            "UPDATE SOLICITUD 
            SET idAlumno = ?, idOferta = ?, fechaSolicitud = ?, estado = ?, favorito = ?
            WHERE id = ?"
        );
        $stmt->execute([
            $solicitud->getIdAlumno(),
            $solicitud->getIdOferta(),
            $solicitud->getFechaSolicitud(),
            $solicitud->getEstado(),
            $solicitud->getFavorito(),
            $solicitud->getId()
        ]);
    }


    public static function delete($id)
    {
        $con = DB::getConnection();
        $stmt = $con->prepare("DELETE FROM SOLICITUD WHERE id = ?");
        $stmt->execute([$id]);
    }

    public static function findByAlumnoYOferta($idAlumno, $idOferta)
{
    $con = DB::getConnection();
    $stmt = $con->prepare("SELECT * FROM SOLICITUD WHERE idAlumno = ? AND idOferta = ?");
    $stmt->execute([$idAlumno, $idOferta]);
    $fila = $stmt->fetch(\PDO::FETCH_ASSOC);

    if ($fila) {
        return new Solicitud(
            $fila['idAlumno'],
            $fila['idOferta'],
            $fila['fechaSolicitud'],
            $fila['estado'],
            $fila['id'],
            $fila['favorito']
        );
    }

    return null;
}


    public static function setFavorito($idSolicitud, $favorito)
    {
        $con = DB::getConnection();
        $stmt = $con->prepare("UPDATE SOLICITUD SET favorito = ? WHERE id = ?");
        return $stmt->execute([$favorito, $idSolicitud]);
    }
}
