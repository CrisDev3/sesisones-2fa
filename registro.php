<?php
require_once 'clases/mysql.inc.php';
require_once 'clases/RegistroUsuario.php';
require_once 'clases/SanitizarEntrada.php';
session_start();

// Crear conexión
$conexion = new mod_db();
$pdo = $conexion->getConexion();

$arrMensaje = [
    'error' => '',
    'success' => '',
    'qr' => '',
    'secret' => ''
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $registro = new RegistroUsuario($pdo);
    $resultado = $registro->procesarRegistro($_POST, $arrMensaje);

    // 🔹 IMPORTANTE: forzamos que si no hay éxito, se mantenga el error visible
    if ($resultado) {
        if (!empty($arrMensaje['success'])) {
            $mensajeMostrar = ['tipo' => 'success', 'texto' => $arrMensaje['success']];
        } else {
            $mensajeMostrar = ['tipo' => 'success', 'texto' => 'Usuario registrado correctamente.'];
        }
    } else {
        if (!empty($arrMensaje['error'])) {
            $mensajeMostrar = ['tipo' => 'error', 'texto' => $arrMensaje['error']];
        } else {
            $mensajeMostrar = ['tipo' => 'error', 'texto' => 'Ocurrió un error inesperado.'];
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Registro de Usuario</title>
<link rel="stylesheet" href="css/cmxform.css" type="text/css" />
<link rel="stylesheet" href="Estilos/Techmania.css" type="text/css" />
<link rel="stylesheet" href="Estilos/general.css" type="text/css">
<style>
form { margin-top:20px; }
input, select { margin-bottom:10px; padding:6px; width:220px; }
button { padding:6px 12px; border:none; border-radius:4px; cursor:pointer; }
button[type=submit] { background:#007bff; color:white; }
button[type=submit]:hover { background:#0056b3; }
.btn-volver { background:#6c757d; color:white; margin-top:10px; }
.btn-volver:hover { background:#5a6268; }
.message {
  margin: 10px auto;
  padding: 10px;
  width: 320px;
  border-radius: 4px;
  text-align: center;
  font-weight: bold;
}
.message.error { background: #f8d7da; color: #721c24; border:1px solid #f5c6cb; }
.message.success { background: #d4edda; color: #155724; border:1px solid #c3e6cb; }
</style>
</head>
<body>
<div align="center">
<h2>Registro de Nuevo Usuario</h2>

<?php if (!empty($mensajeMostrar)): ?>
  <div class="message <?php echo $mensajeMostrar['tipo']; ?>">
    <?php echo $mensajeMostrar['texto']; ?>
  </div>
<?php endif; ?>

<form method="post" action="">
  <label>Nombre:</label><br>
  <input type="text" name="nombre" required><br>

  <label>Apellido:</label><br>
  <input type="text" name="apellido" required><br>

  <label>Correo electrónico:</label><br>
  <input type="email" name="email" required><br>

  <label>Contraseña:</label><br>
  <input type="password" name="clave" required><br>

  <label>Confirmar contraseña:</label><br>
  <input type="password" name="confirmar_clave" required><br>

  <button type="submit">Registrar</button>
</form>

<br>
<button class="btn-volver" onclick="window.location.href='login.php'">Volver al Login</button>

<?php if (!empty($arrMensaje['success'])): ?>
  <h3>Escanea este código QR en Google Authenticator</h3>
  <img class="qr" src="<?php echo htmlspecialchars($arrMensaje['qr']); ?>" alt="QR 2FA">
  <p><strong>Secret:</strong> <?php echo htmlspecialchars($arrMensaje['secret']); ?></p>
<?php endif; ?>

</div>
</body>
</html>
