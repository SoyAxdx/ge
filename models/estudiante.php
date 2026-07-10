<?php
// ============================================
// MODELO: Estudiante
// ============================================

namespace Models;

use Config\Database;
use PDO;
use Exception;

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
        $resultados = $stmt->fetchAll();
        
        // Descifrar datos
        foreach ($resultados as &$row) {
            $row['cedula'] = $this->descifrar($row['cedula']);
            $row['nombre'] = $this->descifrar($row['nombre']);
            $row['apellido'] = $this->descifrar($row['apellido']);
            $row['direccion'] = $this->descifrar($row['direccion']);
            $row['telefono'] = $this->descifrar($row['telefono']);
            $row['email'] = $this->descifrar($row['email']);
        }
        return $resultados;
    }
    
    // ============================================
    // OBTENER ESTUDIANTE POR ID
    // ============================================
    public function obtenerPorId($id) {
        $sql = "SELECT * FROM estudiantes WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($row) {
            $row['cedula'] = $this->descifrar($row['cedula']);
            $row['nombre'] = $this->descifrar($row['nombre']);
            $row['apellido'] = $this->descifrar($row['apellido']);
            $row['direccion'] = $this->descifrar($row['direccion']);
            $row['telefono'] = $this->descifrar($row['telefono']);
            $row['email'] = $this->descifrar($row['email']);
        }
        return $row;
    }
    
    // ============================================
    // CREAR ESTUDIANTE
    // ============================================
    public function crear($datos) {
        $sql = "INSERT INTO estudiantes (cedula, nombre, apellido, fecha_nacimiento, direccion, telefono, email) 
                VALUES (:cedula, :nombre, :apellido, :fecha_nacimiento, :direccion, :telefono, :email)";
        
        $stmt = $this->db->prepare($sql);
        
        return $stmt->execute([
            ':cedula' => $this->cifrar($datos['cedula']),
            ':nombre' => $this->cifrar($datos['nombre']),
            ':apellido' => $this->cifrar($datos['apellido']),
            ':fecha_nacimiento' => $datos['fecha_nacimiento'],
            ':direccion' => $this->cifrar($datos['direccion']),
            ':telefono' => $this->cifrar($datos['telefono']),
            ':email' => $this->cifrar($datos['email'])
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
        
        return $stmt->execute([
            ':id' => $id,
            ':cedula' => $this->cifrar($datos['cedula']),
            ':nombre' => $this->cifrar($datos['nombre']),
            ':apellido' => $this->cifrar($datos['apellido']),
            ':fecha_nacimiento' => $datos['fecha_nacimiento'],
            ':direccion' => $this->cifrar($datos['direccion']),
            ':telefono' => $this->cifrar($datos['telefono']),
            ':email' => $this->cifrar($datos['email'])
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
                WHERE cedula LIKE :termino 
                OR nombre LIKE :termino 
                OR apellido LIKE :termino 
                OR email LIKE :termino
                ORDER BY id DESC";
        
        $stmt = $this->db->prepare($sql);
        $termino = '%' . $termino . '%';
        $stmt->bindParam(':termino', $termino);
        $stmt->execute();
        $resultados = $stmt->fetchAll();
        
        // Descifrar datos
        foreach ($resultados as &$row) {
            $row['cedula'] = $this->descifrar($row['cedula']);
            $row['nombre'] = $this->descifrar($row['nombre']);
            $row['apellido'] = $this->descifrar($row['apellido']);
            $row['direccion'] = $this->descifrar($row['direccion']);
            $row['telefono'] = $this->descifrar($row['telefono']);
            $row['email'] = $this->descifrar($row['email']);
        }
        return $resultados;
    }

// ============================================
// CREAR ESTUDIANTE CON USUARIO AUTOMÁTICO
// ============================================
public function crearConUsuario($datos) {
    // Iniciar transacción
    $this->db->beginTransaction();
    
    try {
        // 1. Crear el usuario (con contraseña temporal)
        $password_temporal = 'tmp' . date('Y') . rand(100, 999);
        $hash = password_hash($password_temporal, PASSWORD_DEFAULT);
        
        $sql = "INSERT INTO usuarios (nombre, apellido, email, password, rol) 
                VALUES (:nombre, :apellido, :email, :password, 'estudiante')";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':nombre' => $datos['nombre'],
            ':apellido' => $datos['apellido'],
            ':email' => $datos['email'],
            ':password' => $hash
        ]);
        
        $usuario_id = $this->db->lastInsertId();
        
        // 2. Crear el estudiante (con el usuario_id)
        $sql = "INSERT INTO estudiantes (usuario_id, cedula, nombre, apellido, fecha_nacimiento, direccion, telefono, email) 
                VALUES (:usuario_id, :cedula, :nombre, :apellido, :fecha_nacimiento, :direccion, :telefono, :email)";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':usuario_id' => $usuario_id,
            ':cedula' => $this->cifrar($datos['cedula']),
            ':nombre' => $this->cifrar($datos['nombre']),
            ':apellido' => $this->cifrar($datos['apellido']),
            ':fecha_nacimiento' => $datos['fecha_nacimiento'],
            ':direccion' => $this->cifrar($datos['direccion']),
            ':telefono' => $this->cifrar($datos['telefono']),
            ':email' => $this->cifrar($datos['email'])
        ]);
        
        $this->db->commit();
        
        return [
            'success' => true,
            'usuario_id' => $usuario_id,
            'password' => $password_temporal
        ];
        
    } catch (Exception $e) {
        $this->db->rollBack();
        return [
            'success' => false,
            'error' => $e->getMessage()
        ];
    }

  } 

  // ============================================
// CIFRADO DE DATOS PERSONALES
// ============================================

/**
 * Cifrar un dato usando AES-256-CBC
 */
private function cifrar($dato) {
    if (empty($dato)) return $dato;
    
    $method = ENCRYPTION_METHOD;
    $key = ENCRYPTION_KEY;
    $iv_length = openssl_cipher_iv_length($method);
    $iv = openssl_random_pseudo_bytes($iv_length);
    $cifrado = openssl_encrypt($dato, $method, $key, 0, $iv);
    return base64_encode($iv . $cifrado);
}

/**
 * Descifrar un dato usando AES-256-CBC
 * Si el dato no está cifrado, devuelve el valor original
 */
private function descifrar($dato_cifrado) {
    if (empty($dato_cifrado)) return $dato_cifrado;
    
    try {
        $method = ENCRYPTION_METHOD;
        $key = ENCRYPTION_KEY;
        $iv_length = openssl_cipher_iv_length($method);
        $datos = base64_decode($dato_cifrado);
        
        // Si no se puede decodificar, es texto plano
        if ($datos === false || strlen($datos) < $iv_length) {
            return $dato_cifrado;
        }
        
        $iv = substr($datos, 0, $iv_length);
        $cifrado = substr($datos, $iv_length);
        $resultado = openssl_decrypt($cifrado, $method, $key, 0, $iv);
        
        // Si falla, devolver el valor original
        return $resultado !== false ? $resultado : $dato_cifrado;
    } catch (Exception $e) {
        // En caso de error, devolver el valor original
        return $dato_cifrado;
    }
}

}
?>