<?php

// namespace Repositorys;


// require_once __DIR__ . '/../Models/Rol.php';
// require_once 'DB.php';

// class RepoRol {

//     public static function findById($id) {
//         $con = DB::getConnection();
//         $stmt = $con->prepare("SELECT * FROM ROL WHERE id = ?");
//         $stmt->execute([$id]);
//         $fila = $stmt->fetch(\PDO::FETCH_ASSOC);

//         if ($fila) {
//             return new Rol(
//                 $fila['nombre'],
//                 $fila['id']
//             );
//         }

//         return null;
//     }

//     public static function findAll() {
//         $con = DB::getConnection();
//         $stmt = $con->prepare("SELECT * FROM ROL");
//         $stmt->execute();
//         $filas = $stmt->fetchAll(\PDO::FETCH_ASSOC);

//         $roles = [];
//         foreach ($filas as $fila) {
//             $roles[] = new Rol(
//                 $fila['nombre'],
//                 $fila['id']
//             );
//         }

//         return $roles;
//     }

//     public static function save($rol) {
//         $con = DB::getConnection();
//         $stmt = $con->prepare("INSERT INTO ROL (nombre) VALUES (?)");
//         $stmt->execute([
//             $rol->getNombre()
//         ]);
//         $rol->setId($con->lastInsertId());
//     }

//     public static function update($rol) {
//         $con = DB::getConnection();
//         $stmt = $con->prepare("UPDATE ROL SET nombre = ? WHERE id = ?");
//         $stmt->execute([
//             $rol->getNombre(),
//             $rol->getId()
//         ]);
//     }

//     public static function delete($id) {
//         $con = DB::getConnection();
//         $stmt = $con->prepare("DELETE FROM ROL WHERE id = ?");
//         $stmt->execute([$id]);
//     }
// }
?>
