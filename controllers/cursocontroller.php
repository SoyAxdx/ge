<?php
// ============================================
// CONTROLADOR: Curso (CRUD completo)
// ============================================

namespace Controllers;

use Models\Curso;
// ✅ Importar funciones de exportación
require_once __DIR__ . '/../helpers/exportar.php';

class CursoController {
    
    private $modelo;
    
    public function __construct() {
    $this->modelo = new Curso();
    
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

         // Verificar CSRF
if (!isset($_POST['csrf_token']) || !verificarTokenCSRF($_POST['csrf_token'])) {
    $_SESSION['error'] = '❌ Error de seguridad. Intenta de nuevo.';
    header('Location: index.php?action=cursos');
    exit();
}

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
        // Verificar CSRF
        if (!isset($_POST['csrf_token']) || !verificarTokenCSRF($_POST['csrf_token'])) {
            $_SESSION['error'] = '❌ Error de seguridad. Intenta de nuevo.';
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

    // ============================================
    // BUSCAR CURSOS
    // ============================================
  public function buscar() {
    $termino = $_GET['termino'] ?? '';
    if (empty($termino)) {
        header('Location: index.php?action=cursos');
        exit();
    }
    
    $cursos = $this->modelo->buscar($termino);
    include_once __DIR__ . '/../views/cursos/index.php';
  }

  // ============================================
// EXPORTAR CURSOS A PDF
// ============================================
public function exportarPDF() {
    ini_set('display_errors', 1);
    error_reporting(E_ALL);
    $cursos = $this->modelo->obtenerTodos();
    
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
        <h1>📋 Reporte de Cursos</h1>
        <p>Total: ' . count($cursos) . ' cursos</p>
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Código</th>
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th>Créditos</th>
                </tr>
            </thead>
            <tbody>';
    
    $contador = 1;
    foreach ($cursos as $c) {
        $html .= '
            <tr>
                <td>' . $contador++ . '</td>
                <td>' . htmlspecialchars($c['codigo']) . '</td>
                <td>' . htmlspecialchars($c['nombre']) . '</td>
                <td>' . htmlspecialchars(substr($c['descripcion'] ?? '', 0, 50)) . '...</td>
                <td>' . $c['creditos'] . '</td>
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
    
    exportarPDF($html, 'Reporte_Cursos');
}

// ============================================
// EXPORTAR CURSOS A EXCEL
// ============================================
public function exportarExcel() {
    ini_set('display_errors', 1);
    error_reporting(E_ALL);
    $cursos = $this->modelo->obtenerTodos();
    
    $datos = [];
    foreach ($cursos as $c) {
        $datos[] = [
            $c['codigo'],
            $c['nombre'],
            $c['descripcion'] ?? '',
            $c['creditos'],
            $c['fecha_creacion']
        ];
    }
    
    $columnas = ['Código', 'Nombre', 'Descripción', 'Créditos', 'Fecha Creación'];
    exportarExcel($datos, $columnas, 'Reporte_Cursos');
}

}
?>