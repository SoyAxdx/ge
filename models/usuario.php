<?php
// ============================================
// MODELO: Usuario
// ============================================

namespace Models;

use Config\Database;
use PDO;

class Usuario {
    
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    // ============================================
    // REGISTRAR NUEVO USUARIO
    // ============================================
    public function registrar($nombre, $apellido, $email, $password, $rol = 'estudiante') {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        
        $sql = "INSERT INTO usuarios (nombre, apellido, email, password, rol) 
                VALUES (:nombre, :apellido, :email, :password, :rol)";
        
        $stmt = $this->db->prepare($sql);
        
        return $stmt->execute([
            ':nombre' => $nombre,
            ':apellido' => $apellido,
            ':email' => $email,
            ':password' => $hash,
            ':rol' => $rol
        ]);
    }
    
    // ============================================
    // INICIAR SESIÓN (LOGIN)
    // ============================================
    public function login($email, $password) {
        $sql = "SELECT * FROM usuarios WHERE email = :email AND activo = 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':email' => $email]);
        
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($usuario && password_verify($password, $usuario['password'])) {
            return $usuario;
        }
        
        return false;
    }
    
    // ============================================
    // OBTENER TODOS LOS USUARIOS
    // ============================================
    public function obtenerTodos() {
        $sql = "SELECT * FROM usuarios ORDER BY id DESC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }
    
    // ============================================
    // OBTENER USUARIO POR ID
    // ============================================
    public function obtenerPorId($id) {
        $sql = "SELECT * FROM usuarios WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>