<?php


define("CLAVE_SECRETA", "primerparcial");
use \Firebase\JWT\JWT;


class Login {


    public static function CrearJwt($email,$tipo)
    {
        $payload = array(
            "email" => $email,
            "type" => $tipo
        );
        
        
        try {
            $jwt = JWT::encode($payload, CLAVE_SECRETA);
            return $jwt;
        } catch (\Throwable $th) {
            echo "Error al codificar el jwt.";
        }
    }


    public static function ValidarJwt($token)
    {
        try {
            $decoded = JWT::decode($token, CLAVE_SECRETA, array('HS256'));
            $array = json_decode(json_encode($decoded), true);
            return $array;
        } catch (\Throwable $th) {
            echo "Error al decodificar el jwt.";
        }
    }


}
?>
