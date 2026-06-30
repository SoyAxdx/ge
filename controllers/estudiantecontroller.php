<?php
// ============================================
// CONTROLADOR: Estudiante (CRUD completo)
// ============================================

namespace Controllers;

use Models\Estudiante;

class EstudianteController {
    
    private $modelo;
    
    public function __construct() {
        $this->modelo = new Estudiante();
        
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
    // LISTAR ESTUDIANTES (READ)
    // ============================================
    public function index() {
        $estudiantes = $this->modelo->obtenerTodos();
        include_once __DIR__ . '/../views/estudiantes/index.php';
    }
    
    // ============================================
    // MOSTRAR FORMULARIO DE CREACIÓN (CREATE)
    // ============================================
    public function crear() {
        include_once __DIR__ . '/../views/estudiantes/crear.php';
    }
    
    // ============================================
    // GUARDAR ESTUDIANTE (CREATE) - CON TODAS LAS VALIDACIONES
    // ============================================
    public function guardar() {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header('Location: index.php?action=estudiantes');
        exit();
    }

    // --- Recoger y NORMALIZAR datos ---
    $cedula = trim($_POST['cedula'] ?? '');
    $nombre = $this->normalizarNombre($_POST['nombre'] ?? '');
    $apellido = $this->normalizarNombre($_POST['apellido'] ?? '');
    $email = $this->normalizarEmail($_POST['email'] ?? '');
    $telefono = trim($_POST['telefono'] ?? '');
    $fecha_nacimiento = $_POST['fecha_nacimiento'] ?? null;
    $direccion = trim($_POST['direccion'] ?? '');

    // ==========================================
    // VALIDACIONES
    // ==========================================

    // 1. Validar cédula panameña
    if (!preg_match('/^[0-9]-[0-9]{2,3}-[0-9]{2,4}$/', $cedula)) {
        $_SESSION['error'] = '❌ Formato de cédula inválido. Usa: 0-000-0000, 0-00-00 o 0-00-000';
        header('Location: index.php?action=estudiante_crear');
        exit();
    }

    // 2. Validar nombre (solo letras y espacios, mínimo 2 caracteres)
    if (!preg_match('/^[a-zA-ZáéíóúñÁÉÍÓÚÑ\s]+$/', $nombre)) {
        $_SESSION['error'] = '❌ El nombre solo debe contener letras.';
        header('Location: index.php?action=estudiante_crear');
        exit();
    }
    if (strlen($nombre) < 2) {
        $_SESSION['error'] = '❌ El nombre debe tener al menos 2 caracteres.';
        header('Location: index.php?action=estudiante_crear');
        exit();
    }

    // 3. Validar apellido
    if (!preg_match('/^[a-zA-ZáéíóúñÁÉÍÓÚÑ\s]+$/', $apellido)) {
        $_SESSION['error'] = '❌ El apellido solo debe contener letras.';
        header('Location: index.php?action=estudiante_crear');
        exit();
    }
    if (strlen($apellido) < 2) {
        $_SESSION['error'] = '❌ El apellido debe tener al menos 2 caracteres.';
        header('Location: index.php?action=estudiante_crear');
        exit();
    }

    // 4. Validar email
    if ($email !== '') {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['error'] = '❌ El correo electrónico no es válido.';
            header('Location: index.php?action=estudiante_crear');
            exit();
        }
        if (!preg_match('/\.(com|es)$/', $email)) {
            $_SESSION['error'] = '❌ El correo debe tener dominio .com o .es.';
            header('Location: index.php?action=estudiante_crear');
            exit();
        }
    }

    // 5. Validar teléfono
    if ($telefono !== '' && !preg_match('/^[0-9]{4}-[0-9]{4}$/', $telefono)) {
        $_SESSION['error'] = '❌ Formato de teléfono inválido. Usa: xxxx-xxxx';
        header('Location: index.php?action=estudiante_crear');
        exit();
    }

    // ==========================================
    // GUARDAR EN BASE DE DATOS (con datos normalizados)
    // ==========================================
    $datos = [
        'cedula' => $cedula,
        'nombre' => $nombre,   // ← Ya normalizado
        'apellido' => $apellido, // ← Ya normalizado
        'fecha_nacimiento' => $fecha_nacimiento,
        'direccion' => $direccion,
        'telefono' => $telefono,
        'email' => $email    // ← Ya normalizado (minúsculas)
    ];

    if ($this->modelo->crear($datos)) {
        $_SESSION['success'] = '✅ Estudiante creado exitosamente.';
    } else {
        $_SESSION['error'] = '❌ Error al crear estudiante.';
    }

    header('Location: index.php?action=estudiantes');
    exit();
  }
    
    // ============================================
    // MOSTRAR FORMULARIO DE EDICIÓN (UPDATE)
    // ============================================
    public function editar() {
        $id = $_GET['id'] ?? 0;
        $estudiante = $this->modelo->obtenerPorId($id);
        
        if (!$estudiante) {
            $_SESSION['error'] = '❌ Estudiante no encontrado.';
            header('Location: index.php?action=estudiantes');
            exit();
        }
        
        include_once __DIR__ . '/../views/estudiantes/editar.php';
    }
    
    // ============================================
// ACTUALIZAR ESTUDIANTE (UPDATE)
// ============================================
public function actualizar() {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header('Location: index.php?action=estudiantes');
        exit();
    }

    // --- Recoger y NORMALIZAR datos ---
    $id = $_POST['id'] ?? 0;
    $cedula = trim($_POST['cedula'] ?? '');
    $nombre = $this->normalizarNombre($_POST['nombre'] ?? '');
    $apellido = $this->normalizarNombre($_POST['apellido'] ?? '');
    $email = $this->normalizarEmail($_POST['email'] ?? '');
    $telefono = trim($_POST['telefono'] ?? '');
    $fecha_nacimiento = $_POST['fecha_nacimiento'] ?? null;
    $direccion = trim($_POST['direccion'] ?? '');

    // ==========================================
    // VALIDACIONES
    // ==========================================

    // 1. Validar que el ID sea válido
    if ($id <= 0) {
        $_SESSION['error'] = '❌ ID de estudiante inválido.';
        header('Location: index.php?action=estudiantes');
        exit();
    }

    // 2. Validar cédula panameña
    if (!preg_match('/^[0-9]-[0-9]{2,3}-[0-9]{2,4}$/', $cedula)) {
        $_SESSION['error'] = '❌ Formato de cédula inválido. Usa: 0-000-0000, 0-00-00 o 0-00-000';
        header('Location: index.php?action=estudiante_editar&id=' . $id);
        exit();
    }

    // 3. Validar nombre (solo letras y espacios, mínimo 2 caracteres)
    if (!preg_match('/^[a-zA-ZáéíóúñÁÉÍÓÚÑ\s]+$/', $nombre)) {
        $_SESSION['error'] = '❌ El nombre solo debe contener letras.';
        header('Location: index.php?action=estudiante_editar&id=' . $id);
        exit();
    }
    if (strlen($nombre) < 2) {
        $_SESSION['error'] = '❌ El nombre debe tener al menos 2 caracteres.';
        header('Location: index.php?action=estudiante_editar&id=' . $id);
        exit();
    }

    // 4. Validar apellido (solo letras y espacios, mínimo 2 caracteres)
    if (!preg_match('/^[a-zA-ZáéíóúñÁÉÍÓÚÑ\s]+$/', $apellido)) {
        $_SESSION['error'] = '❌ El apellido solo debe contener letras.';
        header('Location: index.php?action=estudiante_editar&id=' . $id);
        exit();
    }
    if (strlen($apellido) < 2) {
        $_SESSION['error'] = '❌ El apellido debe tener al menos 2 caracteres.';
        header('Location: index.php?action=estudiante_editar&id=' . $id);
        exit();
    }

    // 5. Validar email (si se ingresó)
    if ($email !== '') {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['error'] = '❌ El correo electrónico no es válido.';
            header('Location: index.php?action=estudiante_editar&id=' . $id);
            exit();
        }
        if (!preg_match('/\.(com|es)$/', $email)) {
            $_SESSION['error'] = '❌ El correo debe tener dominio .com o .es.';
            header('Location: index.php?action=estudiante_editar&id=' . $id);
            exit();
        }
    }

    // 6. Validar teléfono (si se ingresó)
    if ($telefono !== '' && !preg_match('/^[0-9]{4}-[0-9]{4}$/', $telefono)) {
        $_SESSION['error'] = '❌ Formato de teléfono inválido. Usa: xxxx-xxxx';
        header('Location: index.php?action=estudiante_editar&id=' . $id);
        exit();
    }

    // ==========================================
    // ACTUALIZAR EN BASE DE DATOS
    // ==========================================
    $datos = [
        'cedula' => $cedula,
        'nombre' => $nombre,
        'apellido' => $apellido,
        'fecha_nacimiento' => $fecha_nacimiento,
        'direccion' => $direccion,
        'telefono' => $telefono,
        'email' => $email
    ];

    if ($this->modelo->actualizar($id, $datos)) {
        $_SESSION['success'] = '✅ Estudiante actualizado exitosamente.';
    } else {
        $_SESSION['error'] = '❌ Error al actualizar estudiante.';
    }

    header('Location: index.php?action=estudiantes');
    exit();
  }
    
    // ============================================
    // ELIMINAR ESTUDIANTE (DELETE)
    // ============================================
    public function eliminar() {
        $id = $_GET['id'] ?? 0;
        
        if ($this->modelo->eliminar($id)) {
            $_SESSION['success'] = '✅ Estudiante eliminado exitosamente.';
        } else {
            $_SESSION['error'] = '❌ Error al eliminar estudiante.';
        }
        
        header('Location: index.php?action=estudiantes');
        exit();
    }

    // ============================================
// FUNCIONES DE NORMALIZACIÓN (dentro del controlador)
// ============================================

/**
 * Convierte un nombre a formato correcto:
 * - Primera letra de cada palabra en mayúscula
 * - El resto en minúsculas
 * - Elimina espacios múltiples
 */
 private function normalizarNombre($nombre) {
    // Eliminar espacios múltiples
    $nombre = preg_replace('/\s+/', ' ', trim($nombre));
    // Convertir a minúsculas y luego capitalizar cada palabra
    $palabras = explode(' ', $nombre);
    foreach ($palabras as $i => $palabra) {
        if (strlen($palabra) > 0) {
            $palabras[$i] = ucfirst(strtolower($palabra));
        }
    }
    return implode(' ', $palabras);
}

/**
 * Normalizar email: minúsculas y sin espacios
 */
   private function normalizarEmail($email) {
    return strtolower(trim($email));
   }   
}
?>