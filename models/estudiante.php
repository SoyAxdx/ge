<?php
// ============================================
// MODELO: Estudiante
// ============================================

namespace Models;

use Config\Database;
use PDO;

class Estudiante {
    
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    // ============================================
    // OBTENER TODOS LOS ESTUDIANTES
    // ============================================
    public function obtenerTodos() {
        $sql = "SELECT * FROM estudiantes ORDER BY id DESC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }
    
    // ============================================
    // OBTENER ESTUDIANTE POR ID
    // ============================================
    public function obtenerPorId($id) {
        $sql = "SELECT * FROM estudiantes WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    // ============================================
    // CREAR ESTUDIANTE
    // ============================================
    public function crear($datos) {
        $sql = "INSERT INTO estudiantes (cedula, nombre, apellido, fecha_nacimiento, direccion, telefono, email) 
                VALUES (:cedula, :nombre, :apellido, :fecha_nacimiento, :direccion, :telefono, :email)";
        
        $stmt = $this->db->prepare($sql);
        
        return $stmt->execute([
            ':cedula' => $datos['cedula'],
            ':nombre' => $datos['nombre'],
            ':apellido' => $datos['apellido'],
            ':fecha_nacimiento' => $datos['fecha_nacimiento'],
            ':direccion' => $datos['direccion'],
            ':telefono' => $datos['telefono'],
            ':email' => $datos['email']
        ]);
    }
    
    // ============================================
    // ACTUALIZAR ESTUDIANTE
    // ============================================
    public function actualizar($id, $datos) {
        $sql = "UPDATE estudiantes SET 
                cedula = :cedula,
                nombre = :nombre,
                apellido = :apellido,
                fecha_nacimiento = :fecha_nacimiento,
                direccion = :direccion,
                telefono = :telefono,
                email = :email
                WHERE id = :id";
        
        $stmt = $this->db->prepare($sql);
        $datos[':id'] = $id;
        
        return $stmt->execute([
            ':id' => $id,
            ':cedula' => $datos['cedula'],
            ':nombre' => $datos['nombre'],
            ':apellido' => $datos['apellido'],
            ':fecha_nacimiento' => $datos['fecha_nacimiento'],
            ':direccion' => $datos['direccion'],
            ':telefono' => $datos['telefono'],
            ':email' => $datos['email']
        ]);
    }
    
    // ============================================
    // ELIMINAR ESTUDIANTE
    // ============================================
    public function eliminar($id) {
        $sql = "DELETE FROM estudiantes WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }

    // ============================================
// ESTADÍSTICAS PARA EL DASHBOARD
// ============================================

/**
 * Contar total de estudiantes
 */
public function contarTotal() {
    $sql = "SELECT COUNT(*) as total FROM estudiantes";
    $stmt = $this->db->query($sql);
    $resultado = $stmt->fetch();
    return $resultado['total'] ?? 0;
}

// ============================================
// BUSCAR ESTUDIANTES
// ============================================
public function buscar($termino) {
    $sql = "SELECT * FROM estudiantes 
            WHERE nombre LIKE :termino 
            OR apellido LIKE :termino 
            OR cedula LIKE :termino 
            OR email LIKE :termino
            ORDER BY id DESC";
    
    $stmt = $this->db->prepare($sql);
    $termino = '%' . $termino . '%';
    $stmt->bindParam(':termino', $termino);
    $stmt->execute();
    return $stmt->fetchAll();
}

}
?>