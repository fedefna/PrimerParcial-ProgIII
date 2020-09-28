<?php

require_once './ARCHIVOS/file.php';


class Usuario extends file
{
  public $email;
  public $tipo;
  public $password;

  // Constructors
  public function __construct($email, $tipo, $pass)
  {
    $this->email = $email;
    $this->tipo = $tipo;
    $this->password = $pass;
  }

  //GETERS Y SETERS MAGICOS: 

  public function __get($prop)
  {
    return $this->$prop;
  }

  public function __set($prop, $value)
  {
    $this->prop = $value;
  }

  /* Recibe un path. Lee archivos con Usuarios y los devuelve en un array de usuarios. */
  public static function CreateArrayOfUsuarios($path)
  {
    $arrayFile = File::ReadFile($path);
    $arrayOfUsuarios = array();
    foreach ($arrayFile as $key => $value) {
      $newUsuario = new Usuario($value["email"], $value["tipo"], $value["password"]);
      array_push($arrayOfUsuarios, $newUsuario);
    }
    return $arrayOfUsuarios;
  }

  public static function SaveUsuarios($arrayOfUsuarios, $path)
  {
    File::Save($arrayOfUsuarios, $path);
    echo "<br>Archivo guardado.<br>";
  }

  //Si los datos son correctos devuelve el objeto usuario correspondiente
  public static function ValidarUserAndPassword($user, $pass)
  { 
    $arrayOfUsuarios = Usuario::CreateArrayOfUsuarios(ARCHIVO_USUARIOS);
    foreach ($arrayOfUsuarios as $key => $value) {
      if ($value->email == $user && $value->password == $pass) {
        return $value;
      }
    }echo "email o password incorrectos.";
  }

  public static function ValidarEmailUnico($email, $array)
  {
    $aux = true;
    if ((count($array)) > 0) {
      foreach ($array as $key => $value) {
        if ($email == $value->email) {
          $aux = false;
          break;
        }
      }
    }
    return $aux;
  }

}


