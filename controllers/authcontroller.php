<?php
// ============================================
// CONTROLADOR: Autenticación (Login / Registro)
// ============================================

namespace Controllers;

use Models\Usuario;

class AuthController {
    
    private $usuarioModel;
    
    public function __construct() {
        $this->usuarioModel = new Usuario();
        
        // Iniciar sesión si no está iniciada
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }
    
    // ============================================
    // MOSTRAR FORMULARIO DE LOGIN
    // ============================================
    // ============================================
// MOSTRAR FORMULARIO DE LOGIN
// ============================================
public function showLogin() {
    // Si ya está logueado y NO se pide logout forzoso, redirigir al dashboard
    if (isset($_SESSION['usuario_id']) && !isset($_GET['logout'])) {
        header('Location: index.php?action=dashboard');
        exit();
    }
    
    // Si se pide logout forzoso, cerrar sesión
    if (isset($_GET['logout'])) {
        session_destroy();
        session_start();
        // Redirigir a login sin el parámetro logout
        header('Location: index.php?action=login');
        exit();
    }
    
    include_once __DIR__ . '/../views/auth/login.php';
}
    
    // ============================================
    // MOSTRAR FORMULARIO DE REGISTRO
    // ============================================
    public function showRegister() {
        // Si ya está logueado, redirigir al dashboard
        if (isset($_SESSION['usuario_id'])) {
            header('Location: index.php?action=dashboard');
            exit();
        }
        
        include_once __DIR__ . '/../views/auth/register.php';
    }
    
    // ============================================
    // PROCESAR REGISTRO DE USUARIO
    // ============================================
    // ============================================
// PROCESAR REGISTRO DE USUARIO (con validaciones mejoradas)
// ============================================
public function register() {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header('Location: index.php?action=register');
        exit();
    }
    
    // Verificar CSRF
    if (!isset($_POST['csrf_token']) || !verificarTokenCSRF($_POST['csrf_token'])) {
        $_SESSION['error'] = '❌ Error de seguridad. Intenta de nuevo.';
        header('Location: index.php?action=register');
        exit();
    }

    $nombre = trim($_POST['nombre'] ?? '');
    $apellido = trim($_POST['apellido'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $passwordConfirm = $_POST['password_confirm'] ?? '';
    $rol = $_POST['rol'] ?? 'estudiante';  // ✅ NUEVA LÍNEA

    // --- Validación de nombre (solo letras y espacios) ---
if (!preg_match('/^[a-zA-ZáéíóúñÁÉÍÓÚÑ\s]+$/', $nombre)) {
    $_SESSION['error'] = '❌ El nombre solo debe contener letras.';
    header('Location: index.php?action=register');
    exit();
}
if (strlen($nombre) < 2) {
    $_SESSION['error'] = '❌ El nombre debe tener al menos 2 caracteres.';
    header('Location: index.php?action=register');
    exit();
}

// --- Validación de apellido ---
if (!preg_match('/^[a-zA-ZáéíóúñÁÉÍÓÚÑ\s]+$/', $apellido)) {
    $_SESSION['error'] = '❌ El apellido solo debe contener letras.';
    header('Location: index.php?action=register');
    exit();
}
if (strlen($apellido) < 2) {
    $_SESSION['error'] = '❌ El apellido debe tener al menos 2 caracteres.';
    header('Location: index.php?action=register');
    exit();
}

    // --- Validación de email ---
    $email = strtolower($email);
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = '❌ El correo electrónico no es válido.';
        header('Location: index.php?action=register');
        exit();
    }
    if (!preg_match('/\.(com|es)$/', $email)) {
        $_SESSION['error'] = '❌ El correo debe tener dominio .com o .es.';
        header('Location: index.php?action=register');
        exit();
    }

    // --- Validación de contraseña ---
    if (strlen($password) < 6) {
        $_SESSION['error'] = '❌ La contraseña debe tener al menos 6 caracteres.';
        header('Location: index.php?action=register');
        exit();
    }
    if (!preg_match('/[*#$&+]/', $password)) {
        $_SESSION['error'] = '❌ La contraseña debe contener un carácter especial (* # $ & +).';
        header('Location: index.php?action=register');
        exit();
    }
    if ($password !== $passwordConfirm) {
        $_SESSION['error'] = '❌ Las contraseñas no coinciden.';
        header('Location: index.php?action=register');
        exit();
    }

    // --- Intentar registrar (ahora con rol) ---
    if ($this->usuarioModel->registrar($nombre, $apellido, $email, $password, $rol)) {
        $_SESSION['success'] = '✅ Registro exitoso. Ahora puedes iniciar sesión.';
        header('Location: index.php?action=login');
    } else {
        $_SESSION['error'] = '❌ Error al registrar. El email puede estar duplicado.';
        header('Location: index.php?action=register');
    }
    exit();
}
    
    // ============================================
    // PROCESAR LOGIN
    // ============================================
    public function login() {

        // Verificar CSRF
if (!isset($_POST['csrf_token']) || !verificarTokenCSRF($_POST['csrf_token'])) {
    $_SESSION['error'] = '❌ Error de seguridad. Intenta de nuevo.';
    header('Location: index.php?action=login');
    exit();
}

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?action=login');
            exit();
        }
        
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        
        if (empty($email) || empty($password)) {
            $_SESSION['error'] = 'Todos los campos son obligatorios.';
            header('Location: index.php?action=login');
            exit();
        }
        
        $usuario = $this->usuarioModel->login($email, $password);
        
        if ($usuario) {
            $_SESSION['usuario_id'] = $usuario['id'];
            $_SESSION['usuario_nombre'] = $usuario['nombre'];
            $_SESSION['usuario_apellido'] = $usuario['apellido'];
            $_SESSION['usuario_email'] = $usuario['email'];
            $_SESSION['usuario_rol'] = $usuario['rol'];
            
            header('Location: index.php?action=dashboard');
        } else {
            $_SESSION['error'] = '❌ Credenciales incorrectas. Intenta de nuevo.';
            header('Location: index.php?action=login');
        }
        exit();
    }
    
    // ============================================
    // CERRAR SESIÓN
    // ============================================
    public function logout() {
        session_destroy();
        header('Location: index.php?action=login');
        exit();
    }
}
?>