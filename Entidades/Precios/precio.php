<?php

require_once './ARCHIVOS/file.php';


class Precio extends file
{
  public $precio_hora;
  public $precio_estadia;
  public $precio_mensual;

  // Constructors
  public function __construct($precio_hora, $precio_estadia, $precio_mensual)
  {
    $this->precio_hora = $precio_hora;
    $this->precio_estadia = $precio_estadia;
    $this->precio_mensual = $precio_mensual;
  }

  //GETERS Y SETERS MAGICOS: haces solo un get/set que sirve para todos.

  public function __get($prop)
  {
    return $this->$prop;
  }

  public function __set($prop, $value)
  {
    $this->$prop=$value;
  }

  /* Recibe un path. Lee el archivo precio y lo devuelve en un objeto precio. */
  public static function CrearPrecio($path)
  {
    $precio = File::ReadFile($path);
    foreach ($precio as $key => $value) {
      $newPrecio = new Precio($value["precio_hora"],$value["precio_estadia"], $value["precio_mensual"]);
    }
    return $newPrecio;
  }

  public static function SavePrecios($precio, $path)
  {
    File::Save($precio, $path);
    echo "<br>Archivo guardado.<br>";
  }
}
