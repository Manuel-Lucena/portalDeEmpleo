<?php

namespace Repositorys;

use Models\Token;

class RepoToken
{

    public static function findByIdUser($idUser)
    {
        $con = DB::getConnection();
        $stmt = $con->prepare("SELECT * FROM TOKEN WHERE idUser = ?");
        $stmt->execute([$idUser]);
        $fila = $stmt->fetch(\PDO::FETCH_ASSOC);

        if ($fila) {
            return new Token(
                $fila['id'],
                $fila['idUser'],
                $fila['token']
            );
        }

        return null;
    }


    public static function save($idUser, $token)
    {
        $con = DB::getConnection();
        $stmt = $con->prepare("INSERT INTO TOKEN (idUser, token) VALUES (?, ?)");
        $stmt->execute([$idUser, $token]);


    }

    public static function delete($id)
    {
        $con = DB::getConnection();
        $stmt = $con->prepare("DELETE FROM TOKEN WHERE id = ?");
        $stmt->execute([$id]);

        return $stmt->rowCount() > 0;
    }
}
