<?php
require_once 'SanitizarEntrada.php';
require_once 'vendor/autoload.php';

use Sonata\GoogleAuthenticator\GoogleAuthenticator;
use Sonata\GoogleAuthenticator\GoogleQrUrl;

class RegistroUsuario {
    private $pdo;
    private $tabla = "usuarios";
    private $Nombre;
    private $Apellido;
    private $Usuario;
    private $Correo;
    private $Contrasena;
    private $HashGenerado;
    private $Secret2FA;
    private $FechaSistema;

    public function __construct($pdo) {
        $this->pdo = $pdo;
        $this->FechaSistema = date("Y-m-d H:i:s");
    }

    // --- MÉTODO PRINCIPAL ---
    public function procesarRegistro($datos, &$arrMensaje) {
        // Validar y sanitizar entradas
        if (!$this->validarDatos($datos, $arrMensaje)) {
            return false;
        }

        // Validar correo duplicado
        if ($this->existeCorreo($this->Correo)) {
            $arrMensaje['error'] = "El correo ya está registrado.";
            return false;
        }

        // Generar hash y secret 2FA
        $this->generarHash();
        $this->generarSecret2FA();

        // Registrar en base de datos
        if ($this->insertarUsuario($arrMensaje)) {
            $this->generarQr($arrMensaje);
            return true;
        }

        return false;
    }

    // --- MÉTODOS AUXILIARES ---
    private function validarDatos($datos, &$arrMensaje) {
        $errores = [];

        // Validar nombre y apellido
        $this->Nombre = isset($datos["nombre"]) ? SanitizarEntrada::limpiarCadena($datos["nombre"]) : "";
        $this->Apellido = isset($datos["apellido"]) ? SanitizarEntrada::limpiarCadena($datos["apellido"]) : "";

        // Validar email
        if (isset($datos["email"])) {
            $correo = SanitizarEntrada::limpiarCadena($datos["email"]);
            if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
                $errores[] = "El formato del correo no es válido.";
            } else {
                $this->Correo = $correo;
                $this->Usuario = $correo;
            }
        } else {
            $errores[] = "No se recibió el correo electrónico.";
        }

        // Validar contraseñas
        if (empty($datos["clave"]) || empty($datos["confirmar_clave"])) {
            $errores[] = "Debe ingresar y confirmar la contraseña.";
        } elseif ($datos["clave"] !== $datos["confirmar_clave"]) {
            $errores[] = "Las contraseñas no coinciden.";
        } else {
            $this->Contrasena = SanitizarEntrada::limpiarCadena($datos["clave"]);
        }

        if (!empty($errores)) {
            $arrMensaje['error'] = implode("<br>", $errores);
            return false;
        }

        return true;
    }

    private function existeCorreo($correo) {
        $sql = "SELECT COUNT(*) FROM {$this->tabla} WHERE Correo = :correo";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(":correo", $correo);
        $stmt->execute();
        return $stmt->fetchColumn() > 0;
    }

    private function generarHash() {
        $options = ['cost' => 13];
        $this->HashGenerado = password_hash($this->Contrasena, PASSWORD_BCRYPT, $options);
    }

    private function generarSecret2FA() {
        $g = new GoogleAuthenticator();
        $this->Secret2FA = $g->generateSecret();
    }

    private function insertarUsuario(&$arrMensaje) {
        try {
            $sql = "INSERT INTO {$this->tabla} 
                    (Nombre, Apellido, Usuario, Correo, HashMagic, secret_2fa, FechaSistema)
                    VALUES (:Nombre, :Apellido, :Usuario, :Correo, :HashMagic, :secret_2fa, :FechaSistema)";
            $stmt = $this->pdo->prepare($sql);

            $stmt->bindParam(":Nombre", $this->Nombre);
            $stmt->bindParam(":Apellido", $this->Apellido);
            $stmt->bindParam(":Usuario", $this->Usuario);
            $stmt->bindParam(":Correo", $this->Correo);
            $stmt->bindParam(":HashMagic", $this->HashGenerado);
            $stmt->bindParam(":secret_2fa", $this->Secret2FA);
            $stmt->bindParam(":FechaSistema", $this->FechaSistema);

            return $stmt->execute();
        } catch (PDOException $e) {
            $arrMensaje['error'] = "Error al registrar usuario: " . $e->getMessage();
            return false;
        }
    }

    private function generarQr(&$arrMensaje) {
        $nombreApp = "CompanyInfo";
        $qrCodeUrl = GoogleQrUrl::generate($this->Correo, $this->Secret2FA, $nombreApp);

        $arrMensaje['success'] = true;
        $arrMensaje['qr'] = $qrCodeUrl;
        $arrMensaje['secret'] = $this->Secret2FA;
    }
}
?>
