<?php
// config.php

// 1. Configuración de la Base de Datos
define('DB_HOST', 'localhost');       // Host de la base de datos
define('DB_NAME', 'minehive');       // Nombre de la base de datos
define('DB_USER', 'root');            // Usuario de la base de datos
define('DB_PASS', '');                // Contraseña de la base de datos
define('DB_CHARSET', 'utf8mb4');      // Codificación de caracteres

// 2. Configuración de la Aplicación
define('APP_NAME', 'MineHive');       // Nombre de la aplicación
define('APP_URL', 'http://localhost/minehive'); // URL base de la aplicación
define('APP_ROOT', __DIR__);          // Ruta raíz del proyecto

// 3. Configuración de Sesión
session_start();                      // Iniciar sesión
define('SESSION_TIMEOUT', 3600);      // Tiempo de expiración de la sesión en segundos (1 hora)

// 4. Configuración de Seguridad
define('SECRET_KEY', 'tu_clave_secreta_aqui'); // Clave secreta para hashing, tokens, etc.
define('DEBUG_MODE', true);           // Modo de depuración (true para desarrollo, false para producción)

// 5. Conexión a la Base de Datos
try {
    $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Lanzar excepciones en errores
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // Retornar arrays asociativos
        PDO::ATTR_EMULATE_PREPARES   => false,                  // Desactivar emulación de prepared statements
    ];
    $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
} catch (PDOException $e) {
    // Manejo de errores en la conexión
    if (DEBUG_MODE) {
        die("Error de conexión a la base de datos: " . $e->getMessage());
    } else {
        die("Error de conexión. Por favor, inténtelo de nuevo más tarde.");
    }
}

// 6. Funciones de Utilidad
function redirect($url) {
    header("Location: " . APP_URL . $url);
    exit();
}

function sanitize_input($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}

// 7. Manejo de Errores
if (DEBUG_MODE) {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', 0);
    ini_set('display_startup_errors', 0);
    error_reporting(0);
}
?>