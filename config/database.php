<?php
// ============================================
// CONFIGURACIÓN DE BASE DE DATOS
// ============================================

namespace Config;

use PDO;
use PDOException;

// Cargar variables de entorno
require_once __DIR__ . '/../helpers/env.php';
cargarEnv();

class Database {
    
    private static $instance = null;
    private $pdo;
    
    // --- Configuración desde variables de entorno ---
    private $host;
    private $dbname;
    private $username;
    private $password;
    
    // --- Constructor privado (Singleton) ---
    private function __construct() {
        // Obtener valores de las variables de entorno
        $this->host = env('DB_HOST', '127.0.0.1');
        $this->dbname = env('DB_NAME', 'ge');
        $this->username = env('DB_USER', 'root');
        $this->password = env('DB_PASS', '');
        
        try {
            $this->pdo = new PDO(
                "mysql:host={$this->host};dbname={$this->dbname};charset=utf8",
                $this->username,
                $this->password
            );
            
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            
        } catch (PDOException $e) {
            die("❌ Error de conexión a la base de datos: " . $e->getMessage());
        }
    }
    
    // --- Obtener la instancia única de la conexión ---
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    // --- Obtener el objeto PDO para hacer consultas ---
    public function getConnection() {
        return $this->pdo;
    }
}
?>