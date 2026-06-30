<?php
// ============================================
// CONTROLADOR: Dashboard
// ============================================

namespace Controllers;

use Models\Estudiante;
use Models\Curso;
use Models\Inscripcion;

class DashboardController {
    
    private $estudianteModel;
    private $cursoModel;
    private $inscripcionModel;
    
    public function __construct() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    // Verificar autenticación
    if (!isset($_SESSION['usuario_id'])) {
        header('Location: index.php?action=login');
        exit();
    }
    
    // El Dashboard es visible para todos los roles autenticados
    // No necesita verificación de admin
}
    
    // ============================================
    // MOSTRAR DASHBOARD CON ESTADÍSTICAS
    // ============================================
    public function index() {
        // Estadísticas generales
        $total_estudiantes = $this->estudianteModel->contarTotal();
        $total_cursos = $this->cursoModel->contarTotal();
        $total_inscripciones = $this->inscripcionModel->contarTotalInscripciones();
        $promedio_notas = $this->inscripcionModel->promedioNotas();
        
        // Últimos inscritos
        $ultimos_inscritos = $this->inscripcionModel->ultimosInscritos(5);
        
        // Cursos populares
        $cursos_populares = $this->inscripcionModel->cursosPopulares(5);
        
        // Pasar datos a la vista
        include_once __DIR__ . '/../views/dashboard.php';
    }
}
?>