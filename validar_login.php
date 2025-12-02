<?php
session_start();
include ("clases/mysql.inc.php");
include("clases/SanitizarEntrada.php");
include("comunes/loginfunciones.php");
include("clases/objLoginAdmin.php");

$db = new mod_db();

$Usuario = $_POST['usuario'] ?? '';
$ClaveKey = $_POST['contrasena'] ?? '';
$ipRemoto = $_SERVER['REMOTE_ADDR'];

$Logearme = new ValidacionLogin($Usuario, $ClaveKey, $ipRemoto, $db);

if ($Logearme->logger()) {
    $Logearme->autenticar();
    if ($Logearme->getIntentoLogin()) {
        $_SESSION['Usuario'] = $Logearme->getUsuario();
        header("Location: verificar_2fa.php");
        exit;
    } else {
        $_SESSION["emsg"] = 1;
        header("Location: login.php");
        exit;
    }
} else {
    $_SESSION["emsg"] = 1;
    header("Location: login.php");
    exit;
}
?>
