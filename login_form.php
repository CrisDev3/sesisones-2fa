
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Login con 2FA</title>

<link rel="shortcut icon" href="patria/5564844.png">
<link rel="stylesheet" href="css/cmxform.css" type="text/css" />
<link rel="stylesheet" href="Estilos/Techmania.css" type="text/css" />
<link rel="stylesheet" href="Estilos/general.css" type="text/css">
<script src="jquery/jquery-latest.js"></script>
<script src="jquery/jquery.validate.js"></script>

<script>
$(document).ready(function(){
  $("#deteccionUser").validate({
    rules: {
      usuario: "required",
      contrasena: "required",
    }
  });
});

const toggle = () => {
  const input = document.getElementById('contrasena');
  const icon = document.getElementById('toggleContrasena');
  const isPassword = input.type === 'password';
  input.type = isPassword ? 'text' : 'password';
  icon.textContent = isPassword ? '🙈' : '👁️';
};
</script>

<style>
.alerta-error{
  background-color:#fff9f9;
  color:#6a0e0e;
  border:1px solid #eee;
  border-left:5px solid #d32f2f;
  border-radius:4px;
  padding:12px 18px;
  margin:10px auto;
  display:flex;
  align-items:center;
  max-width:450px;
  font-size:15px;
  box-shadow:0 1px 2px rgba(0,0,0,0.05);
}
.btn-registro{
  background:#007BFF;
  color:white;
  border:none;
  border-radius:4px;
  padding:6px 14px;
  cursor:pointer;
  margin-top:12px;
}
.btn-registro:hover{background:#0056b3;}
</style>
</head>

<body>
<div id="wrap">
  <div id="headerlogin"></div>
  <a href=""><img src="img/regresar.gif" alt="Atrás" width="90" height="30"/></a>

  <div align="center">
    <form class="cmxform" id="deteccionUser" name="deteccionUser" method="post" action="validar_login.php">
      <table width="89%" border="0" align="center">
        <tr><td colspan="2" align="center"><h3>Ing. Web | UTP</h3></td></tr>
        <tr>
          <td>Usuario:</td>
          <td><input id="usuario" name="usuario" type="text" minlength="4" /></td>
        </tr>
        <tr>
          <td>Contraseña:</td>
          <td style="position:relative;">
            <input id="contrasena" name="contrasena" type="password" />
            <span id="toggleContrasena" style="position:absolute; right:8px; top:5px; cursor:pointer;" onclick="toggle()">👁️</span>
          </td>
        </tr>
        <tr>
          <td colspan="2" align="center">
            <input name="Submit" type="submit" class="clear" value="Limpiar" />
            <p>(enter para entrar)</p>
            <button type="button" class="btn-registro" onclick="location.href='registro.php'">Registrar nuevo usuario</button>
          </td>
        </tr>
      </table>
    </form>

      <div id="error"><font color="#FF0000">
      <?php
        if (!empty($_SESSION["emsg"]) && $_SESSION["emsg"] == 1) {
         echo '<div class="alerta-error">';
         echo '<strong>¡Error de Autenticación!</strong> Usuario o contraseña incorrectos. Por favor, vuelva a intentarlo.';
         echo '</div>';
        
        // Eliminar la variable de sesión para que no se muestre de nuevo
        unset($_SESSION["emsg"]);
        }
      ?>
      </font>
      <br />
      <br />
      <br />
      </div>
      </table><br />
    </form></div>
    <br />
  </div>

  
  <?php include("comunes/footer.php");?>
</div>
</body>
</html>
