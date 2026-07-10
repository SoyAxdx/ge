<?php
// ============================================
// GESTIÓN ESCOLAR - ROUTER PRINCIPAL
// Proyecto: GE (Gestión Escolar)
// ============================================

// --- Desactivar visualización de errores ---
ini_set('display_errors', 0);
error_reporting(E_ALL);

// --- Manejador de errores fatales ---
function manejarErrorFatal() {
    $error = error_get_last();
    if ($error !== null && in_array($error['type'], [E_ERROR, E_PARSE, E_COMPILE_ERROR])) {
        ob_clean();
        include_once __DIR__ . '/views/errors/500.php';
        exit();
    }
}
register_shutdown_function('manejarErrorFatal');

// --- Cargar archivos necesarios ---
require_once 'config/database.php';
require_once 'models/Usuario.php';
require_once 'models/Estudiante.php';
require_once 'models/Curso.php';
require_once 'models/Inscripcion.php';
require_once 'controllers/AuthController.php';
require_once 'controllers/EstudianteController.php';
require_once 'controllers/CursoController.php';
require_once 'controllers/InscripcionController.php';
require_once 'controllers/DashboardController.php';
require_once 'helpers/functions.php';

require_once 'helpers/functions.php';
require_once 'helpers/env.php';  
cargarEnv();                    

// --- Usar las clases con sus namespaces ---
use Config\Database;
use Controllers\AuthController;
use Controllers\EstudianteController;
use Controllers\CursoController;
use Controllers\InscripcionController;
use Controllers\DashboardController;

// --- Iniciar sesión ---
session_start();

// ✅ Cargar autoload de Composer
require_once __DIR__ . '/vendor/autoload.php';

// --- Obtener la acción desde la URL ---
$action = isset($_GET['action']) && $_GET['action'] !== '' ? $_GET['action'] : 'login';

// ============================================
// ROUTER
// ============================================
switch ($action) {
    
    // ============================================
    // AUTENTICACIÓN (Login / Registro)
    // ============================================
    case 'login':
        $auth = new AuthController();
        $auth->showLogin();
        break;
        
    case 'register':
        $auth = new AuthController();
        $auth->showRegister();
        break;
        
    case 'procesar_registro':
        $auth = new AuthController();
        $auth->register();
        break;
        
    case 'procesar_login':
        $auth = new AuthController();
        $auth->login();
        break;
        
    case 'logout':
        $auth = new AuthController();
        $auth->logout();
        break;
    
    // ============================================
    // DASHBOARD
    // ============================================
    case 'dashboard':
        $controller = new DashboardController();
        $controller->index();
        break;
    
    // ============================================
    // CRUD - ESTUDIANTES
    // ============================================
    case 'estudiantes':
        $controller = new EstudianteController();
        $controller->index();
        break;
        
    case 'estudiante_crear':
        $controller = new EstudianteController();
        $controller->crear();
        break;
        
    case 'estudiante_guardar':
        $controller = new EstudianteController();
        $controller->guardar();
        break;
        
    case 'estudiante_editar':
        $controller = new EstudianteController();
        $controller->editar();
        break;
        
    case 'estudiante_actualizar':
        $controller = new EstudianteController();
        $controller->actualizar();
        break;
        
    case 'estudiante_eliminar':
        $controller = new EstudianteController();
        $controller->eliminar();
        break;
    
    // ============================================
    // CRUD - CURSOS
    // ============================================
    case 'cursos':
        $controller = new CursoController();
        $controller->index();
        break;
        
    case 'curso_crear':
        $controller = new CursoController();
        $controller->crear();
        break;
        
    case 'curso_guardar':
        $controller = new CursoController();
        $controller->guardar();
        break;
        
    case 'curso_editar':
        $controller = new CursoController();
        $controller->editar();
        break;
        
    case 'curso_actualizar':
        $controller = new CursoController();
        $controller->actualizar();
        break;
        
    case 'curso_eliminar':
        $controller = new CursoController();
        $controller->eliminar();
        break;
    
    // ============================================
    // CRUD - INSCRIPCIONES
    // ============================================
    case 'inscripciones':
        $controller = new InscripcionController();
        $controller->index();
        break;
        
    case 'inscripcion_crear':
        $controller = new InscripcionController();
        $controller->crear();
        break;
        
    case 'inscripcion_guardar':
        $controller = new InscripcionController();
        $controller->guardar();
        break;
        
    case 'inscripcion_editar':
        $controller = new InscripcionController();
        $controller->editar();
        break;
        
    case 'inscripcion_actualizar':
        $controller = new InscripcionController();
        $controller->actualizar();
        break;
        
    case 'inscripcion_eliminar':
        $controller = new InscripcionController();
        $controller->eliminar();
        break;

    // ============================================
    // PÁGINAS DE ERROR
    // ============================================
    case 'error_404':
        include_once __DIR__ . '/views/errors/404.php';
        break;

    case 'error_403':
        include_once __DIR__ . '/views/errors/403.php';
        break;

    case 'error_500':
        include_once __DIR__ . '/views/errors/500.php';
        break;
    
    // ============================================
    // RUTA POR DEFECTO (404)
    // ============================================
    default:
        $errorFile = __DIR__ . '/views/errors/404.php';
        if (file_exists($errorFile)) {
            include_once $errorFile;
        } else {
            echo "<h1>404 - Página no encontrada</h1>";
            echo "<p>La acción <strong>'{$action}'</strong> no existe.</p>";
            echo "<a href='index.php?action=login'>Volver al inicio</a>";
        }
        break;

    // ============================================
    // BUSCADORES
    // ============================================
    case 'buscar_estudiantes':
    $controller = new EstudianteController();
    $controller->buscar();
    break;

    case 'buscar_cursos':
    $controller = new CursoController();
    $controller->buscar();
    break;

    case 'buscar_inscripciones':
    $controller = new InscripcionController();
    $controller->buscar();
    break;

    // ============================================
    // EXPORTAR DATOS
    // ============================================
    case 'exportar_estudiantes_pdf':
    $controller = new EstudianteController();
    $controller->exportarPDF();
    break;

    case 'exportar_estudiantes_excel':
    $controller = new EstudianteController();
    $controller->exportarExcel();
    break;

    case 'exportar_cursos_pdf':
    $controller = new CursoController();
    $controller->exportarPDF();
    break;

    case 'exportar_cursos_excel':
    $controller = new CursoController();
    $controller->exportarExcel();
    break;

    case 'exportar_inscripciones_pdf':
    $controller = new InscripcionController();
    $controller->exportarPDF();
    break;

    case 'exportar_inscripciones_excel':
    $controller = new InscripcionController();
    $controller->exportarExcel();
    break;

}

// ============================================
// MANEJO DE ERRORES (después del switch)
// ============================================
// Nota: Los errores de ejecución (como excepciones) se manejan
// en los controladores, no aquí, para no interferir con el router.
?>