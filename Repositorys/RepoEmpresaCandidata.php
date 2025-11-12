<?php
namespace Repositorys;

use Models\Empresa;
use Repositorys\DB;
use Repositorys\RepoUser;

class RepoEmpresaCandidata {

    public static function findAll() {
        $con = DB::getConnection();
        $stmt = $con->prepare("SELECT * FROM EMPRESA_CANDIDATA");
        $stmt->execute();
        $filas = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $empresas = [];
        foreach ($filas as $fila) {
            $empresas[] = new Empresa(
                $fila['idUser'], // 👈 clave correcta
                $fila['idUser'], // también será su idUser real
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

    public static function save($empresa, $user) {
        $con = DB::getConnection();

        try {
            $con->beginTransaction();

            // 1️⃣ Guardar usuario
            RepoUser::save($user);
            $empresa->setIdUser($user->getId());

            // 2️⃣ Guardar empresa candidata
            $stmt = $con->prepare("
                INSERT INTO EMPRESA_CANDIDATA (idUser, nombreEmpresa, telefono, direccion, personaContacto, email, logo)
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

            $con->commit();
            return true;

        } catch (\Exception $e) {
            $con->rollBack();
            throw new \Exception("Error al guardar empresa candidata: " . $e->getMessage());
        }
    }

    public static function delete($idUser) {
        $con = DB::getConnection();
        $stmt = $con->prepare("DELETE FROM EMPRESA_CANDIDATA WHERE idUser = ?");
        $stmt->execute([$idUser]);
        return $stmt->rowCount() > 0;
    }

    public static function updateLogo($idUser, $logo) {
        $con = DB::getConnection();
        $stmt = $con->prepare("UPDATE EMPRESA_CANDIDATA SET logo = ? WHERE idUser = ?");
        $stmt->execute([$logo, $idUser]);
        return $stmt->rowCount() > 0;
    }

    public static function aprobar($idUserCandidata) {
        $con = DB::getConnection();

        try {
            $con->beginTransaction();

            // 1️⃣ Obtener los datos de la empresa candidata
            $stmt = $con->prepare("SELECT * FROM EMPRESA_CANDIDATA WHERE idUser = ?");
            $stmt->execute([$idUserCandidata]);
            $empresa = $stmt->fetch(\PDO::FETCH_ASSOC);

            if (!$empresa) {
                throw new \Exception("No se encontró la empresa candidata con ID $idUserCandidata");
            }

            // 2️⃣ Insertar en EMPRESA directamente (ya existe el user)
            $stmt = $con->prepare("
                INSERT INTO EMPRESA (idUser, nombreEmpresa, telefono, direccion, personaContacto, email, logo)
                VALUES (?, ?, ?, ?, ?, ?, ?)
            ");
            $stmt->execute([
                $idUserCandidata,
                $empresa['nombreEmpresa'],
                $empresa['telefono'],
                $empresa['direccion'],
                $empresa['personaContacto'],
                $empresa['email'],
                $empresa['logo']
            ]);

            // 3️⃣ Eliminar de candidatas
            $stmt = $con->prepare("DELETE FROM EMPRESA_CANDIDATA WHERE idUser = ?");
            $stmt->execute([$idUserCandidata]);

            $con->commit();
            return true;

        } catch (\Exception $e) {
            $con->rollBack();
            throw new \Exception("Error al aprobar empresa: " . $e->getMessage());
        }
    }
}
