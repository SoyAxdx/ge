<?php
// ============================================
// GESTIÓN ESCOLAR - ROUTER PRINCIPAL
// Proyecto: GE (Gestión Escolar)
// ============================================

// --- Cargar autoload manual (por ahora) ---
require_once 'config/database.php';
require_once 'models/Usuario.php';
require_once 'models/Estudiante.php';
require_once 'models/Curso.php';
require_once 'models/Inscripcion.php'; // ✅ NUEVO
require_once 'controllers/AuthController.php';
require_once 'controllers/EstudianteController.php';
require_once 'controllers/CursoController.php';
require_once 'controllers/InscripcionController.php'; // ✅ NUEVO
// --- Cargar autoload manual ---
require_once 'controllers/DashboardController.php';

// --- Usar las clases con sus namespaces ---
use Config\Database;
use Controllers\AuthController;
use Controllers\EstudianteController;
use Controllers\CursoController;
use Controllers\InscripcionController; // ✅ NUEVO
// --- Usar las clases con sus namespaces ---
use Controllers\DashboardController;

// --- Iniciar sesión ---
session_start();

// --- Obtener la acción desde la URL ---
$action = $_GET['action'] ?? 'login';

// --- Router: decidir qué controlador ejecutar ---
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
    
    // --- En el switch, reemplazar case 'dashboard' ---
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
    // CRUD - INSCRIPCIONES  // ✅ NUEVO BLOQUE
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
    // RUTA POR DEFECTO (404)
    // ============================================
    default:
        echo "<h1>404 - Página no encontrada</h1>";
        echo "<p>La acción <strong>'{$action}'</strong> no existe.</p>";
        echo "<a href='index.php?action=login'>Volver al inicio</a>";
        break;
}
?>