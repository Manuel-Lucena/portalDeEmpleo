<?php

namespace Repositorys;


use Models\Oferta;

class RepoOferta
{

    public static function findById($id)
    {
        $con = DB::getConnection();
        $stmt = $con->prepare("SELECT * FROM OFERTA WHERE id = ?");
        $stmt->execute([$id]);
        $fila = $stmt->fetch(\PDO::FETCH_ASSOC);

        if ($fila) {
            return new Oferta(
                $fila['idEmpresa'],
                $fila['titulo'],
                $fila['fechaInicio'],
                $fila['fechaFin'],
                $fila['descripcion'],
                $fila['id']
            );
        }

        return null;
    }

    public static function findByEmpresa($idEmpresa)
    {
        $con = DB::getConnection();
        $stmt = $con->prepare("SELECT * FROM OFERTA WHERE idEmpresa = ?");
        $stmt->execute([$idEmpresa]);
        $filas = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $ofertas = [];
        foreach ($filas as $fila) {
            $ofertas[] = new Oferta(
                $fila['idEmpresa'],
                $fila['titulo'],
                $fila['fechaInicio'],
                $fila['fechaFin'],
                $fila['descripcion'],
                $fila['id']

            );
        }

        return $ofertas;
    }

    public static function findByAlumno($idUser)
    {
        $con = DB::getConnection();

        $stmt = $con->prepare(
            "SELECT o.*
         FROM OFERTA o
         INNER JOIN OFERTA_CICLO oc ON o.id = oc.idOferta
         INNER JOIN CICLO c ON oc.idCiclo = c.id
         INNER JOIN ESTUDIOS e ON c.id = e.idCiclo
         INNER JOIN ALUMNO a ON e.idAlumno = a.id
         WHERE a.idUser = ?"
        );
        $stmt->execute([$idUser]);
        $filas = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $ofertas = [];
        foreach ($filas as $fila) {
            $ofertas[] = new Oferta(
                $fila['idEmpresa'],
                $fila['titulo'],
                $fila['fechaInicio'],
                $fila['fechaFin'],
                $fila['descripcion'],
                $fila['id']
            );
        }

        return $ofertas;
    }


    public static function findAll()
    {
        $con = DB::getConnection();
        $stmt = $con->prepare("SELECT * FROM OFERTA");
        $stmt->execute();
        $filas = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $ofertas = [];
        foreach ($filas as $fila) {
            $ofertas[] = new Oferta(
                $fila['idEmpresa'],
                $fila['titulo'],
                $fila['fechaInicio'],
                $fila['fechaFin'],
                $fila['descripcion'],
                $fila['id']
            );
        }

        return $ofertas;
    }

    public static function save($oferta, $ofertaCiclo = null)
    {
        $con = DB::getConnection();

        try {
            $con->beginTransaction();


            $stmt = $con->prepare("INSERT INTO OFERTA (idEmpresa, titulo, fechaInicio, fechaFin, descripcion) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([
                $oferta->getIdEmpresa(),
                $oferta->getTitulo(),
                $oferta->getFechaInicio(),
                $oferta->getFechaFin(),
                $oferta->getDescripcion()
            ]);
            $oferta->setId($con->lastInsertId());


            $ofertaCiclo->setIdOferta($oferta->getId());
            RepoOfertaCiclo::save($ofertaCiclo);


            $con->commit();
        } catch (\PDOException $e) {
            $con->rollBack();
            throw $e;
        }
    }

    public static function update($oferta)
    {
        $con = DB::getConnection();
        $stmt = $con->prepare(
            "UPDATE OFERTA SET idEmpresa = ?, titulo = ?, descripcion = ?, fechaInicio = ?, fechaFin = ? WHERE id = ?"
        );
        $stmt->execute([
            $oferta->getIdEmpresa(),
            $oferta->getTitulo(),
            $oferta->getFechaInicio(),
            $oferta->getFechaFin(),
            $oferta->getDescripcion(),
            $oferta->getId()
        ]);
    }

    public static function delete($id)
    {
        $con = DB::getConnection();
        $stmt = $con->prepare("DELETE FROM OFERTA WHERE id = ?");
        $stmt->execute([$id]);
    }
}
