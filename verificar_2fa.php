<?php
session_start();
require_once 'vendor/autoload.php';
require_once 'clases/mysql.inc.php';

use Sonata\GoogleAuthenticator\GoogleAuthenticator;

$db = new mod_db();
$pdo = $db->getConexion();
$usuario = $_SESSION['Usuario'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $codigo = $_POST['codigo_2fa'];
    $stmt = $pdo->prepare("SELECT secret_2fa FROM usuarios WHERE Usuario = ?");
    $stmt->execute([$usuario]);
    $secret = $stmt->fetchColumn();

    if ($secret) {
        $g = new GoogleAuthenticator();
        if ($g->checkCode($secret, $codigo)) {
            $_SESSION['autenticado'] = "SI";
            header("Location: formularios/PanelControl.php");
            exit;
        } else {
            $error = "Código incorrecto. Intente nuevamente.";
        }
    } else {
        $error = "Usuario no encontrado.";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Verificación 2FA</title>
<link rel="stylesheet" href="css/cmxform.css" type="text/css" />
<link rel="stylesheet" href="Estilos/Techmania.css" type="text/css" />
<link rel="stylesheet" href="Estilos/general.css" type="text/css">
<style>
.alerta-error{background:#fff9f9;color:#6a0e0e;border-left:5px solid #d32f2f;padding:10px;margin:15px auto;width:400px;text-align:center;}
</style>
</head>
<body>
<div align="center">
  <h2>Verificación en dos pasos</h2>
  <p>Introduce el código de Google Authenticator</p>
  <?php if (!empty($error)) echo "<div class='alerta-error'>$error</div>"; ?>
  <form method="post" action="">
    <input type="text" name="codigo_2fa" maxlength="6" required />
    <br><br>
    <button type="submit">Verificar</button>
  </form>
</div>
</body>
</html>
