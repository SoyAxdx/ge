<?php
// ============================================
// CONTROLADOR: Estudiante (CRUD completo)
// ============================================

namespace Controllers;

use Models\Estudiante;
use Exception;

// ✅ Importar funciones de exportación
require_once __DIR__ . '/../helpers/exportar.php';

// ✅ Cargar variables de entorno para cifrado
require_once __DIR__ . '/../helpers/env.php';
cargarEnv();

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
    
    // Verificar rol de administrador
    if ($_SESSION['usuario_rol'] !== 'admin') {
        header('Location: index.php?action=error_403');
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
    // Activar errores para depuración
    ini_set('display_errors', 1);
    error_reporting(E_ALL);

    try {
        // Verificar CSRF
        if (!isset($_POST['csrf_token']) || !verificarTokenCSRF($_POST['csrf_token'])) {
            $_SESSION['error'] = '❌ Error de seguridad. Intenta de nuevo.';
            header('Location: index.php?action=estudiantes');
            exit();
        }

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

        // 2. Validar nombre
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
        // CREAR ESTUDIANTE CON USUARIO AUTOMÁTICO
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

        // ✅ Usar el método que crea estudiante + usuario
        $resultado = $this->modelo->crearConUsuario($datos);

        if ($resultado['success']) {
            $_SESSION['success'] = '✅ Estudiante creado exitosamente. Contraseña temporal: <strong>' . $resultado['password'] . '</strong>';
        } else {
            $_SESSION['error'] = '❌ Error al crear estudiante: ' . $resultado['error'];
        }

        header('Location: index.php?action=estudiantes');
        exit();

    } catch (Exception $e) {
        // Capturar cualquier error y mostrarlo
        echo "Error: " . $e->getMessage();
        echo "<br>Archivo: " . $e->getFile();
        echo "<br>Línea: " . $e->getLine();
        exit();
    }
}
    
    // ============================================
// ACTUALIZAR ESTUDIANTE (UPDATE)
// ============================================
public function actualizar() {

    // Verificar CSRF
if (!isset($_POST['csrf_token']) || !verificarTokenCSRF($_POST['csrf_token'])) {
    $_SESSION['error'] = '❌ Error de seguridad. Intenta de nuevo.';
    header('Location: index.php?action=estudiantes');
    exit();
}

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

   // ============================================
   // BUSCAR ESTUDIANTES
   // ============================================
   public function buscar() {
    $termino = $_GET['termino'] ?? '';
    if (empty($termino)) {
        header('Location: index.php?action=estudiantes');
        exit();
    }
    
    $estudiantes = $this->modelo->buscar($termino);
    include_once __DIR__ . '/../views/estudiantes/index.php';
  }
//METODO DE EXPORTAR PDF PRUEBA
/*public function exportarPDF() {
    // Activar errores
    ini_set('display_errors', 1);
    error_reporting(E_ALL);
    
    try {
        // Datos de prueba simples
        $html = '<h1>Prueba de PDF</h1><p>Este es un PDF de prueba.</p>';
        
        $dompdf = new Dompdf\Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $dompdf->stream('prueba.pdf', ['Attachment' => true]);
        exit();
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
        exit();
    }
}
    */
  public function exportarPDF() {
    ini_set('display_errors', 1);
    error_reporting(E_ALL);
    $estudiantes = $this->modelo->obtenerTodos();
    
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
        <h1>📋 Reporte de Estudiantes</h1>
        <p>Total: ' . count($estudiantes) . ' estudiantes</p>
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Cédula</th>
                    <th>Nombre</th>
                    <th>Apellido</th>
                    <th>Email</th>
                    <th>Teléfono</th>
                </tr>
            </thead>
            <tbody>';
    
    $contador = 1;
    foreach ($estudiantes as $e) {
        $html .= '
            <tr>
                <td>' . $contador++ . '</td>
                <td>' . htmlspecialchars($e['cedula']) . '</td>
                <td>' . htmlspecialchars($e['nombre']) . '</td>
                <td>' . htmlspecialchars($e['apellido']) . '</td>
                <td>' . htmlspecialchars($e['email']) . '</td>
                <td>' . htmlspecialchars($e['telefono']) . '</td>
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
    
    exportarPDF($html, 'Reporte_Estudiantes');
}


public function exportarExcel() {
    ini_set('display_errors', 1);
    error_reporting(E_ALL);
    $estudiantes = $this->modelo->obtenerTodos();
    
    $datos = [];
    foreach ($estudiantes as $e) {
        $datos[] = [
            $e['cedula'],
            $e['nombre'],
            $e['apellido'],
            $e['email'],
            $e['telefono'],
            $e['fecha_registro']
        ];
    }
    
    $columnas = ['Cédula', 'Nombre', 'Apellido', 'Email', 'Teléfono', 'Fecha Registro'];
    exportarExcel($datos, $columnas, 'Reporte_Estudiantes');
}

}
?>