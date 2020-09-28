<?php

require_once './ARCHIVOS/file.php';


class Ingreso extends file
{
  public $patente;
  public $fecha_ingreso;
  public $tipo;

  // Constructors
  public function __construct($patente, $tipo, $fecha_ingreso)
  {
    $this->patente = $patente;
    $this->tipo = $tipo;
    $this->fecha_ingreso = Ingreso::GeFormattedDate();
  }

  //GETERS Y SETERS MAGICOS:

  public function __get($prop)
  {
    return $this->$prop;
  }

  public function __set($prop, $value)
  {
    $this->$prop=$value;
  }

  /* Recibe un path. Lee archivos con ingresos y los devuelve en un array de ingresos. */
  public static function CreateArrayOfIngresos($path)
  {
    $arrayFile = File::ReadFile($path);
    $arrayOfIngresos = array();
    foreach ($arrayFile as $key => $value) {
      $newIng = new Ingreso($value["patente"],$value["tipo"], $value["fecha_ingreso"]);
      array_push($arrayOfIngresos, $newIng);
    }
    return $arrayOfIngresos;
  }

  public static function SaveIngresos($arrayOfIngresos, $path)
  {
    File::Save($arrayOfIngresos, $path);
    echo "<br>Archivo guardado.<br>";
  }

  //Devuelve la fecha actual en formato: Dia/hora:minuto

  static function GeFormattedDate()
  {
    $return = getdate();
    $formattedDate = "Dia: ".$return['mday'] . ". Hora" . $return['hours'] . ":" . $return['minutes'];
    return $formattedDate;
  }
}
