<?php
// ============================================
// MODELO: Curso
// ============================================

namespace Models;

use Config\Database;
use PDO;

class Curso {
    
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    // ============================================
    // OBTENER TODOS LOS CURSOS
    // ============================================
    public function obtenerTodos() {
        $sql = "SELECT * FROM cursos ORDER BY id DESC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }
    
    // ============================================
    // OBTENER CURSO POR ID
    // ============================================
    public function obtenerPorId($id) {
        $sql = "SELECT * FROM cursos WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    // ============================================
    // CREAR CURSO
    // ============================================
    public function crear($datos) {
        $sql = "INSERT INTO cursos (codigo, nombre, descripcion, creditos) 
                VALUES (:codigo, :nombre, :descripcion, :creditos)";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':codigo' => $datos['codigo'],
            ':nombre' => $datos['nombre'],
            ':descripcion' => $datos['descripcion'],
            ':creditos' => $datos['creditos']
        ]);
    }
    
    // ============================================
    // ACTUALIZAR CURSO
    // ============================================
    public function actualizar($id, $datos) {
        $sql = "UPDATE cursos SET 
                codigo = :codigo,
                nombre = :nombre,
                descripcion = :descripcion,
                creditos = :creditos
                WHERE id = :id";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':id' => $id,
            ':codigo' => $datos['codigo'],
            ':nombre' => $datos['nombre'],
            ':descripcion' => $datos['descripcion'],
            ':creditos' => $datos['creditos']
        ]);
    }
    
    // ============================================
    // ELIMINAR CURSO
    // ============================================
    public function eliminar($id) {
        $sql = "DELETE FROM cursos WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }

    // ============================================
// ESTADÍSTICAS PARA EL DASHBOARD
// ============================================

/**
 * Contar total de cursos
 */
public function contarTotal() {
    $sql = "SELECT COUNT(*) as total FROM cursos";
    $stmt = $this->db->query($sql);
    $resultado = $stmt->fetch();
    return $resultado['total'] ?? 0;
}

    // ============================================
    // BUSCAR CURSOS
    // ============================================
  public function buscar($termino) {
    $sql = "SELECT * FROM cursos 
            WHERE nombre LIKE :termino 
            OR codigo LIKE :termino 
            OR descripcion LIKE :termino
            ORDER BY id DESC";
    
    $stmt = $this->db->prepare($sql);
    $termino = '%' . $termino . '%';
    $stmt->bindParam(':termino', $termino);
    $stmt->execute();
    return $stmt->fetchAll();
  }

}
?>