<?php

namespace Repositorys;

use Models\Alumno;

class RepoAlumno
{

    public static function findById($id)
    {
        $con = DB::getConnection();
        $stmt = $con->prepare("SELECT * FROM ALUMNO WHERE id = ?");
        $stmt->execute([$id]);
        $fila = $stmt->fetch(\PDO::FETCH_ASSOC);

        if ($fila) {
            return new Alumno(
                $fila['id'],
                $fila['idUser'],
                $fila['nombre'],
                $fila['email'],
                $fila['fecha_nacimiento'],
                $fila['direccion'],
                $fila['telefono'],
                $fila['curriculum'],
                $fila['foto']
            );
        }

        return null;
    }


    public static function findByNombre($nombre)
    {
        $con = DB::getConnection();
        $stmt = $con->prepare("SELECT * FROM ALUMNO WHERE nombre LIKE ?");
        $stmt->execute(["%$nombre%"]);
        $filas = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $alumnos = [];
        foreach ($filas as $fila) {
            $alumnos[] = new Alumno(
                $fila['id'],
                $fila['idUser'],
                $fila['nombre'],
                $fila['email'],
                $fila['fecha_nacimiento'],
                $fila['direccion'],
                $fila['telefono'],
                $fila['curriculum'],
                $fila['foto']
            );
        }

        return $alumnos;
    }

    public static function findAllOrderByNombre()
    {
        $con = DB::getConnection();
        $stmt = $con->prepare("SELECT * FROM ALUMNO ORDER BY NOMBRE ASC");
        $stmt->execute();
        $filas = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $alumnos = [];
        foreach ($filas as $fila) {
            $alumnos[] = new Alumno(
                $fila['id'],
                $fila['idUser'],
                $fila['nombre'],
                $fila['email'],
                $fila['fecha_nacimiento'],
                $fila['direccion'],
                $fila['telefono'],
                $fila['curriculum'],
                $fila['foto']
            );
        }

        return $alumnos;
    }


    public static function findByIdUser($idUser)
    {
        $con = DB::getConnection();
        $stmt = $con->prepare("SELECT * FROM ALUMNO WHERE idUser = ?");
        $stmt->execute([$idUser]);
        $fila = $stmt->fetch(\PDO::FETCH_ASSOC);

        if ($fila) {
            return new Alumno(
                $fila['id'],
                $fila['idUser'],
                $fila['nombre'],
                $fila['email'],
                $fila['fecha_nacimiento'],
                $fila['direccion'],
                $fila['telefono'],
                $fila['curriculum'],
                $fila['foto']
            );
        }

        return null;
    }

    public static function findAll()
    {
        $con = DB::getConnection();
        $stmt = $con->prepare("SELECT * FROM ALUMNO");
        $stmt->execute();
        $filas = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $alumnos = [];
        foreach ($filas as $fila) {
            $alumnos[] = new Alumno(
                $fila['id'],
                $fila['idUser'],
                $fila['nombre'],
                $fila['email'],
                $fila['fecha_nacimiento'],
                $fila['direccion'],
                $fila['telefono'],
                $fila['curriculum'],
                $fila['foto']
            );
        }

        return $alumnos;
    }

    public static function save($alumno)
    {
        $con = DB::getConnection();
        $stmt = $con->prepare("
        INSERT INTO ALUMNO (idUser, nombre, email, fecha_nacimiento, direccion, telefono, curriculum, foto) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)
    ");
        $stmt->execute([
            $alumno->getIdUser(),
            $alumno->getNombre(),
            $alumno->getEmail(),
            $alumno->getFechaNacimiento(),
            $alumno->getDireccion(),
            $alumno->getTelefono(),
            $alumno->getCurriculum(),
            $alumno->getFoto()
        ]);

        
        $alumno->setId($con->lastInsertId());
    }


    public static function update($alumno)
    {
        $con = DB::getConnection();
        $stmt = $con->prepare("
            UPDATE ALUMNO 
            SET nombre = ?, email = ?, fecha_nacimiento = ?, direccion = ?, telefono = ?, curriculum = ?, foto = ?, idUser = ? 
            WHERE id = ?
        ");
        $stmt->execute([
            $alumno->getNombre(),
            $alumno->getEmail(),
            $alumno->getFechaNacimiento() ?? null,
            $alumno->getDireccion() ?? null,
            $alumno->getTelefono() ?? null,
            $alumno->getCurriculum(),
            $alumno->getFoto(),
            $alumno->getIdUser(),
            $alumno->getId()
        ]);
    }

    public static function delete($id)
    {
        try {
            $con = DB::getConnection();


            $stmt = $con->prepare("SELECT idUser FROM ALUMNO WHERE id = ?");
            $stmt->execute([$id]);
            $alumno = $stmt->fetch(\PDO::FETCH_ASSOC);

            if (!$alumno) {
                return false;
            }


            $stmt = $con->prepare("DELETE FROM ALUMNO WHERE id = ?");
            $stmt->execute([$id]);


            $stmt = $con->prepare("DELETE FROM USER WHERE id = ?");
            $stmt->execute([$alumno['idUser']]);

            return $stmt->rowCount() > 0;
        } catch (\PDOException $e) {
            error_log("Error en RepoAlumno::delete: " . $e->getMessage());
            throw new \Exception("Error al eliminar el alumno: " . $e->getMessage());
        }
    }
}
