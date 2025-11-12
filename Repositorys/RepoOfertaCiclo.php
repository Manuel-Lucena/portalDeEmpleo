<?php

namespace Repositorys;
use Models\OfertaCiclo;

require_once __DIR__ . '/../Models/OfertaCiclo.php';
require_once 'DB.php';

class RepoOfertaCiclo {

    public static function findById($id) {
        $con = DB::getConnection();
        $stmt = $con->prepare("SELECT * FROM OFERTA_CICLO WHERE id = ?");
        $stmt->execute([$id]);
        $fila = $stmt->fetch(\PDO::FETCH_ASSOC);

        if ($fila) {
            return new OfertaCiclo(
                $fila['idOferta'],
                $fila['idCiclo'],
                $fila['id']
            );
        }

        return null;
    }

    public static function findAll() {
        $con = DB::getConnection();
        $stmt = $con->prepare("SELECT * FROM OFERTA_CICLO");
        $stmt->execute();
        $filas = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $ofertaCiclos = [];
        foreach ($filas as $fila) {
            $ofertaCiclos[] = new OfertaCiclo(
                $fila['idOferta'],
                $fila['idCiclo'],
                $fila['id']
            );
        }

        return $ofertaCiclos;
    }

    public static function save($ofertaCiclo) {
        $con = DB::getConnection();
        $stmt = $con->prepare("INSERT INTO OFERTA_CICLO (idOferta, idCiclo) VALUES (?, ?)");
        $stmt->execute([
            $ofertaCiclo->getIdOferta(),
            $ofertaCiclo->getIdCiclo()
        ]);
        $ofertaCiclo->setId($con->lastInsertId());
    }

    public static function update($ofertaCiclo) {
        $con = DB::getConnection();
        $stmt = $con->prepare("UPDATE OFERTA_CICLO SET idOferta = ?, idCiclo = ? WHERE id = ?");
        $stmt->execute([
            $ofertaCiclo->getIdOferta(),
            $ofertaCiclo->getIdCiclo(),
            $ofertaCiclo->getId()
        ]);
    }

    public static function delete($id) {
        $con = DB::getConnection();
        $stmt = $con->prepare("DELETE FROM OFERTA_CICLO WHERE id = ?");
        $stmt->execute([$id]);
    }
}
?>
