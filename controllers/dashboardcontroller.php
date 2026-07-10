<?php
// ============================================
// CONTROLADOR: Dashboard
// ============================================

namespace Controllers;

use Models\Estudiante;
use Models\Curso;
use Models\Inscripcion;
use Exception;

class DashboardController {
    
    private $estudianteModel;
    private $cursoModel;
    private $inscripcionModel;
    
    public function __construct() {
        // Inicializar los modelos
        $this->estudianteModel = new Estudiante();
        $this->cursoModel = new Curso();
        $this->inscripcionModel = new Inscripcion();
        
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        // Verificar autenticación
        if (!isset($_SESSION['usuario_id'])) {
            header('Location: index.php?action=login');
            exit();
        }
    }
    
    // ============================================
    // MOSTRAR DASHBOARD CON ESTADÍSTICAS
    // ============================================
    public function index() {
        // Activar errores para depuración
        ini_set('display_errors', 1);
        error_reporting(E_ALL);
        
        try {
            // Estadísticas generales
            $total_estudiantes = $this->estudianteModel->contarTotal();
            $total_cursos = $this->cursoModel->contarTotal();
            $total_inscripciones = $this->inscripcionModel->contarTotalInscripciones();
            $promedio_notas = $this->inscripcionModel->promedioNotas();
            
            // Últimos inscritos
            $ultimos_inscritos = $this->inscripcionModel->ultimosInscritos(5);
            
            // Cursos populares
            $cursos_populares = $this->inscripcionModel->cursosPopulares(5);
            
            // ✅ DATOS PARA GRÁFICOS (con valores por defecto si fallan)
            try {
                $datos_barras = $this->getDatosGraficoBarras();
                $datos_lineas = $this->getDatosGraficoLineas();
            } catch (Exception $e) {
                $datos_barras = [];
                $datos_lineas = [];
            }
            
            // Preparar datos para Chart.js
            $labels_barras = [];
            $data_barras = [];
            foreach ($datos_barras as $item) {
                if (isset($item['nombre']) && isset($item['total'])) {
                    $labels_barras[] = $item['nombre'];
                    $data_barras[] = (int) $item['total'];
                }
            }
            
            $labels_lineas = [];
            $data_lineas = [];
            foreach ($datos_lineas as $item) {
                if (isset($item['mes']) && isset($item['total'])) {
                    $labels_lineas[] = $item['mes'];
                    $data_lineas[] = (int) $item['total'];
                }
            }
            
            // Pasar datos a la vista
            include_once __DIR__ . '/../views/dashboard.php';
            
        } catch (Exception $e) {
            // Si hay error, mostrar mensaje
            echo "Error en Dashboard: " . $e->getMessage();
            echo "<br>Archivo: " . $e->getFile();
            echo "<br>Línea: " . $e->getLine();
            exit();
        }
    }

    // ============================================
    // DATOS PARA GRÁFICOS
    // ============================================

    /**
     * Obtener datos para el gráfico de estudiantes por curso
     */
    public function getDatosGraficoBarras() {
        $sql = "SELECT c.nombre, COUNT(ec.estudiante_id) as total 
                FROM cursos c
                LEFT JOIN estudiantes_cursos ec ON c.id = ec.curso_id
                GROUP BY c.id
                ORDER BY total DESC";
        
        $stmt = $this->inscripcionModel->getConnection()->query($sql);
        return $stmt->fetchAll();
    }

    /**
     * Obtener datos para el gráfico de inscripciones por mes
     */
    public function getDatosGraficoLineas() {
        $sql = "SELECT DATE_FORMAT(fecha_inscripcion, '%Y-%m') as mes, 
                       COUNT(*) as total 
                FROM estudiantes_cursos 
                GROUP BY mes 
                ORDER BY mes ASC 
                LIMIT 12";
        
        $stmt = $this->inscripcionModel->getConnection()->query($sql);
        return $stmt->fetchAll();
    }
}
?>