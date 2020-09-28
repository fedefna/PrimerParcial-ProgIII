<?php

class file {

    /* Recibe un path. Lee archivos con datos separados por '*'.
       Devuelve un array con una linea por posicion 
        (cada linea es otro array con los datos separados sin los '*'). */

    protected static function ReadFile($path)
    {
      //echo "<br>*****READ FILE*****.<br>";
      $file = fopen($path, 'a+');
      fclose($file);
      $string = file_get_contents($path);
      $arrayFile = json_decode($string,true);
      if ($arrayFile==NULL) {
        $arrayFile = Array();
      }
      //echo "<br>Array que devuelvo:<br>";
      //print_r($arrayFile);
      //echo "<br>*****READ FILE*****.<br>";
      return $arrayFile;
    }


    protected static function Save($arrayFile, $path)
    {
      //echo "<br>*****SAVE*****.<br>";
      $file = fopen($path, 'w');

      //echo "<br>Array recibido:<br>";
      //var_dump($arrayFile);
      //echo "<br>";
      //print_r($arrayFile);
      //echo "<br>encode:<br>";
      //print_r(json_encode($arrayFile));

      $fwrite = fwrite($file, json_encode($arrayFile));
      fclose($file);
      //echo "<br>*****SAVE*****.<br>";
    }


    public static function MostrarArchivo($path)
    {
      $arrayFile = File::ReadFile($path);
      print_r (json_encode($arrayFile));
    }
}
?>
