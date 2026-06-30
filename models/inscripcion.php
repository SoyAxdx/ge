<?php
// ============================================
// MODELO: Inscripcion (estudiantes_cursos)
// ============================================

namespace Models;

use Config\Database;
use PDO;

class Inscripcion {
    
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    // ============================================
    // OBTENER TODAS LAS INSCRIPCIONES
    // ============================================
    public function obtenerTodas() {
        $sql = "SELECT ec.*, 
                       e.nombre as estudiante_nombre, 
                       e.apellido as estudiante_apellido,
                       c.nombre as curso_nombre,
                       c.codigo as curso_codigo
                FROM estudiantes_cursos ec
                JOIN estudiantes e ON ec.estudiante_id = e.id
                JOIN cursos c ON ec.curso_id = c.id
                ORDER BY ec.id DESC";
        
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }
    
    // ============================================
    // OBTENER INSCRIPCIÓN POR ID
    // ============================================
    public function obtenerPorId($id) {
        $sql = "SELECT ec.*, 
                       e.nombre as estudiante_nombre, 
                       e.apellido as estudiante_apellido,
                       c.nombre as curso_nombre,
                       c.codigo as curso_codigo
                FROM estudiantes_cursos ec
                JOIN estudiantes e ON ec.estudiante_id = e.id
                JOIN cursos c ON ec.curso_id = c.id
                WHERE ec.id = :id";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    // ============================================
    // OBTENER INSCRIPCIONES POR ESTUDIANTE
    // ============================================
    public function obtenerPorEstudiante($estudiante_id) {
        $sql = "SELECT ec.*, c.nombre as curso_nombre, c.codigo as curso_codigo
                FROM estudiantes_cursos ec
                JOIN cursos c ON ec.curso_id = c.id
                WHERE ec.estudiante_id = :estudiante_id
                ORDER BY ec.id DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':estudiante_id' => $estudiante_id]);
        return $stmt->fetchAll();
    }
    
    // ============================================
    // OBTENER INSCRIPCIONES POR CURSO
    // ============================================
    public function obtenerPorCurso($curso_id) {
        $sql = "SELECT ec.*, 
                       e.nombre as estudiante_nombre, 
                       e.apellido as estudiante_apellido
                FROM estudiantes_cursos ec
                JOIN estudiantes e ON ec.estudiante_id = e.id
                WHERE ec.curso_id = :curso_id
                ORDER BY ec.id DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':curso_id' => $curso_id]);
        return $stmt->fetchAll();
    }
    
    // ============================================
    // CREAR INSCRIPCIÓN
    // ============================================
    public function crear($estudiante_id, $curso_id) {
        $sql = "INSERT INTO estudiantes_cursos (estudiante_id, curso_id) 
                VALUES (:estudiante_id, :curso_id)";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':estudiante_id' => $estudiante_id,
            ':curso_id' => $curso_id
        ]);
    }
    
    // ============================================
    // ACTUALIZAR NOTA DE INSCRIPCIÓN
    // ============================================
    public function actualizarNota($id, $nota_final) {
        $sql = "UPDATE estudiantes_cursos SET nota_final = :nota_final WHERE id = :id";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':id' => $id,
            ':nota_final' => $nota_final
        ]);
    }
    
    // ============================================
    // ELIMINAR INSCRIPCIÓN
    // ============================================
    public function eliminar($id) {
        $sql = "DELETE FROM estudiantes_cursos WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }
    
    // ============================================
    // VERIFICAR SI YA ESTÁ INSCRITO
    // ============================================
    public function yaInscrito($estudiante_id, $curso_id) {
        $sql = "SELECT id FROM estudiantes_cursos 
                WHERE estudiante_id = :estudiante_id AND curso_id = :curso_id";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':estudiante_id' => $estudiante_id,
            ':curso_id' => $curso_id
        ]);
        return $stmt->fetch() ? true : false;
    }
    
    // ============================================
    // CONTAR INSCRIPCIONES POR CURSO
    // ============================================
    public function contarPorCurso($curso_id) {
        $sql = "SELECT COUNT(*) as total FROM estudiantes_cursos WHERE curso_id = :curso_id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':curso_id' => $curso_id]);
        $resultado = $stmt->fetch();
        return $resultado['total'] ?? 0;
    }

    // ============================================
// ESTADÍSTICAS PARA EL DASHBOARD
// ============================================

/**
 * Obtener total de inscripciones
 */
public function contarTotalInscripciones() {
    $sql = "SELECT COUNT(*) as total FROM estudiantes_cursos";
    $stmt = $this->db->query($sql);
    $resultado = $stmt->fetch();
    return $resultado['total'] ?? 0;
}

/**
 * Obtener promedio de notas general
 */
public function promedioNotas() {
    $sql = "SELECT AVG(nota_final) as promedio FROM estudiantes_cursos WHERE nota_final IS NOT NULL";
    $stmt = $this->db->query($sql);
    $resultado = $stmt->fetch();
    return round($resultado['promedio'] ?? 0, 2);
}

/**
 * Obtener los últimos 5 estudiantes inscritos
 */
public function ultimosInscritos($limite = 5) {
    $sql = "SELECT ec.*, 
                   e.nombre as estudiante_nombre, 
                   e.apellido as estudiante_apellido,
                   c.nombre as curso_nombre
            FROM estudiantes_cursos ec
            JOIN estudiantes e ON ec.estudiante_id = e.id
            JOIN cursos c ON ec.curso_id = c.id
            ORDER BY ec.fecha_inscripcion DESC
            LIMIT :limite";
    
    $stmt = $this->db->prepare($sql);
    $stmt->bindValue(':limite', $limite, \PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll();
}

/**
 * Obtener los cursos con más estudiantes
 */
public function cursosPopulares($limite = 5) {
    $sql = "SELECT c.id, c.nombre, c.codigo, 
                   COUNT(ec.estudiante_id) as total_estudiantes
            FROM cursos c
            LEFT JOIN estudiantes_cursos ec ON c.id = ec.curso_id
            GROUP BY c.id
            ORDER BY total_estudiantes DESC
            LIMIT :limite";
    
    $stmt = $this->db->prepare($sql);
    $stmt->bindValue(':limite', $limite, \PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll();
}
}
?>