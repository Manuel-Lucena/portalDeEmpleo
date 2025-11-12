<?php

namespace Repositorys;

use Models\Empresa;
use Models\User;

class RepoEmpresa {
public static function findById($id) {
    $con = DB::getConnection();
    $stmt = $con->prepare("SELECT * FROM EMPRESA WHERE idUser = ?");
    $stmt->execute([$id]);
    $fila = $stmt->fetch(\PDO::FETCH_ASSOC);

    if ($fila) {
        return new Empresa(
            $fila['id'],       
            $fila['idUser'],
            $fila['nombreEmpresa'],
            $fila['telefono'],
            $fila['direccion'],
            $fila['personaContacto'],
            $fila['email'],
            $fila['logo']
        );
    }

    return null;
}


 public static function findAll() {
    $con = DB::getConnection();
    $stmt = $con->prepare("SELECT * FROM EMPRESA");
    $stmt->execute();
    $filas = $stmt->fetchAll(\PDO::FETCH_ASSOC);

    $empresas = [];
    foreach ($filas as $fila) {
        $empresas[] = new Empresa(
            null,                   
            $fila['idUser'],         
            $fila['nombreEmpresa'], 
            $fila['telefono'],       
            $fila['direccion'],     
            $fila['personaContacto'],
            $fila['email'],         
            $fila['logo']            
        );
    }

    return $empresas; 
}


   
    public static function save(Empresa $empresa, User $user = null) {
        $con = DB::getConnection();

        try {
            $con->beginTransaction();

            if ($user !== null) {
                RepoUser::save($user);
                $empresa->setIdUser($user->getId());
            }

            $stmt = $con->prepare("
                INSERT INTO EMPRESA (idUser, nombreEmpresa, telefono, direccion, personaContacto, email, logo) 
                VALUES (?, ?, ?, ?, ?, ?, ?)
            ");
            $stmt->execute([
                $empresa->getIdUser(),
                $empresa->getNombreEmpresa(),
                $empresa->getTelefono(),
                $empresa->getDireccion(),
                $empresa->getPersonaContacto(),
                $empresa->getEmail(),
                $empresa->getLogo()
            ]);

            $empresa->setId($con->lastInsertId());

            $con->commit();

        } catch (\Exception $e) {
            $con->rollBack();
            throw new \Exception("Error al guardar la empresa: " . $e->getMessage());
        }
    }

    public static function update($empresa) {
        $con = DB::getConnection();
        $stmt = $con->prepare(
            "UPDATE EMPRESA 
             SET nombreEmpresa = ?, telefono = ?, direccion = ?, personaContacto = ?, email = ?, logo = ? 
             WHERE idUser = ?"
        );
        $stmt->execute([
            $empresa->getNombreEmpresa(),
            $empresa->getTelefono(),
            $empresa->getDireccion(),
            $empresa->getPersonaContacto(),
            $empresa->getEmail(),
            $empresa->getLogo(),
            $empresa->getIdUser()
        ]);
    }

    public static function delete($id) {
        $con = DB::getConnection();
        $stmt = $con->prepare("DELETE FROM EMPRESA WHERE idUser = ?");
        $stmt->execute([$id]);
    }

    public static function updateLogo($idUser, $logo) {
    $con = DB::getConnection();
    $stmt = $con->prepare("UPDATE EMPRESA SET logo = ? WHERE idUser = ?");
    $stmt->execute([$logo, $idUser]);
    return $stmt->rowCount() > 0;
}
}
