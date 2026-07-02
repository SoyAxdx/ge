<?php
// ============================================
// CARGADOR DE VARIABLES DE ENTORNO (.env)
// ============================================

/**
 * Carga las variables de entorno desde el archivo .env
 */
function cargarEnv() {
    $archivo = __DIR__ . '/../.env';
    
    if (!file_exists($archivo)) {
        return;
    }
    
    $lineas = file($archivo, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    
    foreach ($lineas as $linea) {
        // Ignorar comentarios
        if (strpos(trim($linea), '#') === 0) {
            continue;
        }
        
        // Dividir en clave y valor
        $partes = explode('=', $linea, 2);
        if (count($partes) === 2) {
            $clave = trim($partes[0]);
            $valor = trim($partes[1]);
            
            // Eliminar comillas si existen
            if (strpos($valor, '"') === 0 && strrpos($valor, '"') === strlen($valor) - 1) {
                $valor = substr($valor, 1, -1);
            }
            if (strpos($valor, "'") === 0 && strrpos($valor, "'") === strlen($valor) - 1) {
                $valor = substr($valor, 1, -1);
            }
            
            $_ENV[$clave] = $valor;
            putenv("$clave=$valor");
        }
    }
}

/**
 * Obtiene el valor de una variable de entorno
 */
function env($clave, $defecto = null) {
    $valor = getenv($clave);
    if ($valor === false) {
        return $defecto;
    }
    return $valor;
}
?>