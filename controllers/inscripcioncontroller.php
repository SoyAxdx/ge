<?php
// ============================================
// CONTROLADOR: Inscripcion
// ============================================

namespace Controllers;

use Models\Inscripcion;
use Models\Estudiante;
use Models\Curso;

class InscripcionController {
    
    private $modelo;
    private $estudianteModel;
    private $cursoModel;
    
    public function __construct() {
        $this->modelo = new Inscripcion();
        $this->estudianteModel = new Estudiante();
        $this->cursoModel = new Curso();
        
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        if (!isset($_SESSION['usuario_id'])) {
            header('Location: index.php?action=login');
            exit();
        }
    }
    
    // ============================================
    // LISTAR INSCRIPCIONES (READ)
    // ============================================
    public function index() {
        $inscripciones = $this->modelo->obtenerTodas();
        include_once __DIR__ . '/../views/inscripciones/index.php';
    }
    
    // ============================================
    // MOSTRAR FORMULARIO DE CREACIÓN (CREATE)
    // ============================================
    public function crear() {
        $estudiantes = $this->estudianteModel->obtenerTodos();
        $cursos = $this->cursoModel->obtenerTodos();
        include_once __DIR__ . '/../views/inscripciones/crear.php';
    }
    
    // ============================================
    // GUARDAR INSCRIPCIÓN (CREATE)
    // ============================================
    public function guardar() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?action=inscripciones');
            exit();
        }
        
        $estudiante_id = (int) ($_POST['estudiante_id'] ?? 0);
        $curso_id = (int) ($_POST['curso_id'] ?? 0);
        
        if ($estudiante_id <= 0 || $curso_id <= 0) {
            $_SESSION['error'] = '❌ Debes seleccionar un estudiante y un curso.';
            header('Location: index.php?action=inscripcion_crear');
            exit();
        }
        
        // Verificar si ya está inscrito
        if ($this->modelo->yaInscrito($estudiante_id, $curso_id)) {
            $_SESSION['error'] = '❌ Este estudiante ya está inscrito en este curso.';
            header('Location: index.php?action=inscripcion_crear');
            exit();
        }
        
        if ($this->modelo->crear($estudiante_id, $curso_id)) {
            $_SESSION['success'] = '✅ Estudiante inscrito en el curso exitosamente.';
        } else {
            $_SESSION['error'] = '❌ Error al inscribir estudiante.';
        }
        
        header('Location: index.php?action=inscripciones');
        exit();
    }
    
    // ============================================
    // MOSTRAR FORMULARIO DE EDICIÓN DE NOTA (UPDATE)
    // ============================================
    public function editar() {
        $id = $_GET['id'] ?? 0;
        $inscripcion = $this->modelo->obtenerPorId($id);
        
        if (!$inscripcion) {
            $_SESSION['error'] = '❌ Inscripción no encontrada.';
            header('Location: index.php?action=inscripciones');
            exit();
        }
        
        include_once __DIR__ . '/../views/inscripciones/editar.php';
    }
    
    // ============================================
    // ACTUALIZAR NOTA DE INSCRIPCIÓN (UPDATE)
    // ============================================
    public function actualizar() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?action=inscripciones');
            exit();
        }
        
        $id = $_POST['id'] ?? 0;
        $nota_final = (float) ($_POST['nota_final'] ?? 0);
        
        // Validar nota (0-100)
        if ($nota_final < 0 || $nota_final > 100) {
            $_SESSION['error'] = '❌ La nota debe estar entre 0 y 100.';
            header('Location: index.php?action=inscripcion_editar&id=' . $id);
            exit();
        }
        
        if ($this->modelo->actualizarNota($id, $nota_final)) {
            $_SESSION['success'] = '✅ Nota actualizada exitosamente.';
        } else {
            $_SESSION['error'] = '❌ Error al actualizar nota.';
        }
        
        header('Location: index.php?action=inscripciones');
        exit();
    }
    
    // ============================================
    // ELIMINAR INSCRIPCIÓN (DELETE)
    // ============================================
    public function eliminar() {
        $id = $_GET['id'] ?? 0;
        
        if ($this->modelo->eliminar($id)) {
            $_SESSION['success'] = '✅ Inscripción eliminada exitosamente.';
        } else {
            $_SESSION['error'] = '❌ Error al eliminar inscripción.';
        }
        
        header('Location: index.php?action=inscripciones');
        exit();
    }
}
?>