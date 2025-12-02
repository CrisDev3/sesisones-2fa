<?PHP
class SanitizarEntrada {

    
    // Sanitiza una cadena eliminando espacios y etiquetas HTML
    public static function limpiarCadena($cadena) {
        return trim(strip_tags($cadena));
    }

       public static function CadTitulo($cadena) {
        // Elimina etiquetas HTML
        $cadena = strip_tags($cadena);
        // Elimina espacios al inicio y al final
        $cadena = trim($cadena);
        // Convierte todo a minúsculas y capitaliza cada palabra
        $cadena = ucwords(strtolower($cadena));
        return $cadena;
    }

    // --- Elimina espacios extra y normaliza ---
    public static function limpiarEspacios($cadena) {
        // Elimina etiquetas HTML y espacios extremos
        $cadena = trim(strip_tags($cadena));
        // Reemplaza múltiples espacios por uno solo
        $cadena = preg_replace('/\s+/', ' ', $cadena);
        return $cadena;
    }

}//SanitizarEntrada

//$nombre = "<b>Juan</b> ";
//$nombreLimpio = SanitizarEntrada::limpiarCadena($nombre);  
//echo "la salida es: ".$nombre."<br>";
?>