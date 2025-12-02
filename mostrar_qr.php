<?php
session_start();

if (!isset($_SESSION['qr'], $_SESSION['secret'], $_SESSION['correo'])) {
    header('Location: registro.php');
    exit();
}

$qr = $_SESSION['qr'];
$secret = $_SESSION['secret'];
$correo = $_SESSION['correo'];

// Limpia sesión (opcional, para no regenerar en refresh)
unset($_SESSION['qr'], $_SESSION['secret']);
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Código QR 2FA</title>
<style>
body { text-align:center; font-family:Arial, sans-serif; }
img.qr { margin-top:20px; }
.btn { margin-top:20px; padding:8px 15px; background:#2c7be5; color:#fff; border:none; border-radius:5px; cursor:pointer; }
.btn:hover { background:#1a5fc2; }
</style>
</head>
<body>
<h2>Registro completado con éxito</h2>
<p><strong><?php echo htmlspecialchars($correo); ?></strong>, escanea el siguiente código QR con Google Authenticator:</p>
<img class="qr" src="<?php echo htmlspecialchars($qr); ?>" alt="QR 2FA">
<p><strong>Secret:</strong> <?php echo htmlspecialchars($secret); ?></p>

<button class="btn" onclick="location.href='login.php'">Ir al Login</button>
</body>
</html>
