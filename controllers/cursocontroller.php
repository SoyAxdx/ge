<?php
// ============================================
// CONTROLADOR: Curso (CRUD completo)
// ============================================

namespace Controllers;

use Models\Curso;

class CursoController {
    
    private $modelo;
    
    public function __construct() {
        $this->modelo = new Curso();
        
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        if (!isset($_SESSION['usuario_id'])) {
            header('Location: index.php?action=login');
            exit();
        }
    }
    
    // ============================================
    // LISTAR CURSOS (READ)
    // ============================================
    public function index() {
        $cursos = $this->modelo->obtenerTodos();
        include_once __DIR__ . '/../views/cursos/index.php';
    }
    
    // ============================================
    // MOSTRAR FORMULARIO DE CREACIÓN (CREATE)
    // ============================================
    public function crear() {
        include_once __DIR__ . '/../views/cursos/crear.php';
    }
    
    // ============================================
    // GUARDAR CURSO (CREATE)
    // ============================================
    public function guardar() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?action=cursos');
            exit();
        }
        
        $datos = [
            'codigo' => trim($_POST['codigo'] ?? ''),
            'nombre' => trim($_POST['nombre'] ?? ''),
            'descripcion' => trim($_POST['descripcion'] ?? ''),
            'creditos' => (int) ($_POST['creditos'] ?? 3)
        ];
        
        if ($this->modelo->crear($datos)) {
            $_SESSION['success'] = '✅ Curso creado exitosamente.';
        } else {
            $_SESSION['error'] = '❌ Error al crear curso.';
        }
        
        header('Location: index.php?action=cursos');
        exit();
    }
    
    // ============================================
    // MOSTRAR FORMULARIO DE EDICIÓN (UPDATE)
    // ============================================
    public function editar() {
        $id = $_GET['id'] ?? 0;
        $curso = $this->modelo->obtenerPorId($id);
        
        if (!$curso) {
            $_SESSION['error'] = '❌ Curso no encontrado.';
            header('Location: index.php?action=cursos');
            exit();
        }
        
        include_once __DIR__ . '/../views/cursos/editar.php';
    }
    
    // ============================================
    // ACTUALIZAR CURSO (UPDATE)
    // ============================================
    public function actualizar() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?action=cursos');
            exit();
        }
        
        $id = $_POST['id'] ?? 0;
        
        $datos = [
            'codigo' => trim($_POST['codigo'] ?? ''),
            'nombre' => trim($_POST['nombre'] ?? ''),
            'descripcion' => trim($_POST['descripcion'] ?? ''),
            'creditos' => (int) ($_POST['creditos'] ?? 3)
        ];
        
        if ($this->modelo->actualizar($id, $datos)) {
            $_SESSION['success'] = '✅ Curso actualizado exitosamente.';
        } else {
            $_SESSION['error'] = '❌ Error al actualizar curso.';
        }
        
        header('Location: index.php?action=cursos');
        exit();
    }
    
    // ============================================
    // ELIMINAR CURSO (DELETE)
    // ============================================
    public function eliminar() {
        $id = $_GET['id'] ?? 0;
        
        if ($this->modelo->eliminar($id)) {
            $_SESSION['success'] = '✅ Curso eliminado exitosamente.';
        } else {
            $_SESSION['error'] = '❌ Error al eliminar curso.';
        }
        
        header('Location: index.php?action=cursos');
        exit();
    }
}
?>