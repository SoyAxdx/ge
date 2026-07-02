<?php
// ============================================
// CONTROLADOR: Inscripcion
// ============================================

namespace Controllers;

use Models\Inscripcion;
use Models\Estudiante;
use Models\Curso;
// ✅ Importar funciones de exportación
require_once __DIR__ . '/../helpers/exportar.php';

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
    
    // Verificar autenticación
    if (!isset($_SESSION['usuario_id'])) {
        header('Location: index.php?action=login');
        exit();
    }
    
    // Verificar rol de administrador
    if ($_SESSION['usuario_rol'] !== 'admin') {
        header('Location: index.php?action=error_403');
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

        // Verificar CSRF
        if (!isset($_POST['csrf_token']) || !verificarTokenCSRF($_POST['csrf_token'])) {
            $_SESSION['error'] = '❌ Error de seguridad. Intenta de nuevo.';
            header('Location: index.php?action=inscripciones');
            exit();
        }

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
        // Verificar CSRF
        if (!isset($_POST['csrf_token']) || !verificarTokenCSRF($_POST['csrf_token'])) {
            $_SESSION['error'] = '❌ Error de seguridad. Intenta de nuevo.';
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

    // ============================================
    // BUSCAR INSCRIPCIONES
    // ============================================
    public function buscar() {
    $termino = $_GET['termino'] ?? '';
    if (empty($termino)) {
        header('Location: index.php?action=inscripciones');
        exit();
    }
    
    $inscripciones = $this->modelo->buscar($termino);
    include_once __DIR__ . '/../views/inscripciones/index.php';
  }

  // ============================================
// EXPORTAR INSCRIPCIONES A PDF
// ============================================
public function exportarPDF() {
    ini_set('display_errors', 1);
    error_reporting(E_ALL);
    $inscripciones = $this->modelo->obtenerTodas();
    
    $html = '
    <html>
    <head>
        <meta charset="UTF-8">
        <style>
            body { font-family: Arial, sans-serif; }
            h1 { text-align: center; color: #1a5276; }
            table { width: 100%; border-collapse: collapse; margin-top: 20px; }
            th { background: #1a5276; color: white; padding: 8px; }
            td { border: 1px solid #ddd; padding: 8px; }
            tr:nth-child(even) { background: #f2f2f2; }
        </style>
    </head>
    <body>
        <h1>📋 Reporte de Inscripciones</h1>
        <p>Total: ' . count($inscripciones) . ' inscripciones</p>
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Estudiante</th>
                    <th>Curso</th>
                    <th>Código</th>
                    <th>Fecha</th>
                    <th>Nota</th>
                </tr>
            </thead>
            <tbody>';
    
    $contador = 1;
    foreach ($inscripciones as $i) {
        $html .= '
            <tr>
                <td>' . $contador++ . '</td>
                <td>' . htmlspecialchars($i['estudiante_nombre'] . ' ' . $i['estudiante_apellido']) . '</td>
                <td>' . htmlspecialchars($i['curso_nombre']) . '</td>
                <td>' . htmlspecialchars($i['curso_codigo']) . '</td>
                <td>' . $i['fecha_inscripcion'] . '</td>
                <td>' . ($i['nota_final'] !== null ? number_format($i['nota_final'], 2) : 'Sin nota') . '</td>
            </tr>';
    }
    
    $html .= '
            </tbody>
        </table>
        <p style="text-align:center; margin-top:20px; color:#888; font-size:12px;">
            Generado el ' . date('d/m/Y H:i:s') . '
        </p>
    </body>
    </html>';
    
    exportarPDF($html, 'Reporte_Inscripciones');
}

// ============================================
// EXPORTAR INSCRIPCIONES A EXCEL
// ============================================
public function exportarExcel() {
    ini_set('display_errors', 1);
    error_reporting(E_ALL);
    $inscripciones = $this->modelo->obtenerTodas();
    
    $datos = [];
    foreach ($inscripciones as $i) {
        $datos[] = [
            $i['estudiante_nombre'] . ' ' . $i['estudiante_apellido'],
            $i['curso_nombre'],
            $i['curso_codigo'],
            $i['fecha_inscripcion'],
            $i['nota_final'] !== null ? number_format($i['nota_final'], 2) : 'Sin nota'
        ];
    }
    
    $columnas = ['Estudiante', 'Curso', 'Código', 'Fecha Inscripción', 'Nota'];
    exportarExcel($datos, $columnas, 'Reporte_Inscripciones');
}
    
}
?>