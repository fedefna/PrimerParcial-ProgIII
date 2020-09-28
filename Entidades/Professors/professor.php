<?php

require_once './ARCHIVOS/file.php';


class Professor extends file
{
  // Properties NO SE PONEN LOS TIPOS DE DATOS
  //private $_id;
  public $_nombre;
  public $_legajo;

  // Constructors
  public function __construct($nombre, $legajo)
  {
    //$this->_id = $id;
    $this->_nombre = $nombre;
    $this->_legajo = $legajo;
  }

  //GETERS Y SETERS MAGICOS: haces solo un get/set que sirve para todos.

  public function __get($prop)
  {
    return $this->$prop;
  }

  public function __set($prop, $value)
  {
    $this->$prop = $value;
  }

  public function __toString()
  {
    return '{"Nombre": "' . $this->_nombre . '", "Legajo": ' . $this->_legajo . '}';
  }

  /* Recibe un path. Lee archivos con Professores y los devuelve en un array de Professores. */
  public static function CreateArrayOfProfessors($path)
  {
    $arrayFile = File::ReadFile($path);
    //echo "<br>vardump arrayfile<br>";
    //var_dump($arrayFile);
    $arrayOfProfessors = array();
    foreach ($arrayFile as $key => $value) {
      $newProfessor = new Professor($value["_nombre"], $value["_legajo"]);
      array_push($arrayOfProfessors, $newProfessor);
    }
    //echo "<br>vardump arrayOfProfessors<br>";
    //var_dump($arrayOfProfessors);
    return $arrayOfProfessors;
  }

  public static function ValidarLegajo($legajo, $array)
  {
    //echo "<br>*****Validar LEGAJO******<br>";
    //var_dump($array);
    $aux = true;
    if ((count($array)) > 0) {
      foreach ($array as $key => $value) {
        if ($legajo == $value->_legajo) {
          $aux = false;
          break;
        }
      }
    }
    return $aux;
  }

  public static function SaveProfessors($arrayOfProfessors, $path)
  {
    File::Save($arrayOfProfessors, $path);
    echo "<br>Archivo guardado.<br>";
  }
}
