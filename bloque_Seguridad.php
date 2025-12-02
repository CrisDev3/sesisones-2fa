<?php
// --- Bloque de seguridad ---
session_start();

// Comprueba que el usuario está autenticado
if (!isset($_SESSION['autenticado']) || $_SESSION['autenticado'] != "SI") {
    // Si no existe la variable de sesión, redirige a la página de login
    unset($_SESSION['Usuario']); // Libera la variable de sesión registrada
    session_destroy(); // Elimina la sesión actual
    header("Location: login.php");
    exit();
}

// --- Función auxiliar: nvl ---
// Devuelve el valor de la variable si existe, de lo contrario devuelve un valor por defecto
function nvl(&$var, $default = "") {
    return isset($var) ? $var : $default;
}
?>
