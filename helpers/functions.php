<?php
// ============================================
// FUNCIONES AUXILIARES PARA CSRF
// ============================================

/**
 * Generar un token CSRF y almacenarlo en la sesión
 */
function generarTokenCSRF() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    
    return $_SESSION['csrf_token'];
}

/**
 * Mostrar el campo oculto del token CSRF en un formulario
 */
function campoTokenCSRF() {
    $token = generarTokenCSRF();
    return '<input type="hidden" name="csrf_token" value="' . $token . '">';
}

/**
 * Verificar si el token CSRF enviado es válido
 */
function verificarTokenCSRF($token) {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    if (empty($_SESSION['csrf_token']) || empty($token)) {
        return false;
    }
    
    return hash_equals($_SESSION['csrf_token'], $token);
}
?>