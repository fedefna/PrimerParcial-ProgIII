<?php
require_once __DIR__ . '/vendor/autoload.php';

use \Firebase\JWT\JWT;

require_once './ARCHIVOS/file.php';
require_once './Login/login.php';
require_once '.\Entidades\Usuarios\usuario.php';
require_once '.\Entidades\Precios\precio.php';
require_once '.\Entidades\ingresos\ingreso.php';


define("ARCHIVO_USUARIOS", ".\Entidades\USUARIOS\usuarios.json");
define("ARCHIVO_PRECIOS", ".\Entidades\Precios\precios.json");
define("ARCHIVO_INGRESOS", ".\Entidades\Ingresos\autos.json");


$method = $_SERVER['REQUEST_METHOD'] ?? '';
$path_info = $_SERVER['PATH_INFO'] ?? '';

switch ($method) {
    case 'POST': //AGREGAR RECURSO

        switch ($path_info) {


            case '/registro':
                /* 1. (POST) registro. Registrar un usuario con los siguientes datos: email, tipo de usuario y password.
El tipo de usuario puede ser admin o user. Validar que el mail no esté registrado previamente. */
                if (isset($_POST['email']) && ($_POST['tipo'] == "admin" || $_POST['tipo'] == "user") && isset($_POST['password']) && filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
                    $arrayOfUsuarios = Usuario::CreateArrayOfUsuarios(ARCHIVO_USUARIOS);
                    if (Usuario::ValidarEmailUnico($_POST['email'], $arrayOfUsuarios)) {
                        $newUser = new Usuario($_POST['email'], $_POST['tipo'], $_POST['password']);
                        array_push($arrayOfUsuarios, $newUser);
                        Usuario::SaveUsuarios($arrayOfUsuarios, ARCHIVO_USUARIOS);
                    } else echo "<br>Email repetido.<br>";
                } else echo "<br>Parametros insuficientes/Invalidos.<br>";
                break;


            case '/login':
                /* 2. (POST) login: Los usuarios deberán loguearse y se les devolverá un token con email y tipo en caso de estar
registrados, caso contrario se informará el error. */
                if (isset($_POST['email']) && isset($_POST['password']) && filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
                    $newUser = Usuario::ValidarUserAndPassword($_POST['email'], $_POST['password']);
                    $jwt = login::CrearJwt($newUser->email, $newUser->tipo);
                    print_r($jwt);
                }
                break;


            case '/precio':
                /* 3. (POST) precio: Solo los administradores podrán cargar el precio por hora, por estadía y mensual en el
archivo precios.xxx. */
                if (isset($_SERVER['HTTP_TOKEN'])) {
                    $decoded = Login::ValidarJwt($_SERVER['HTTP_TOKEN']);
                    if ($decoded["type"] == 'admin') {
                        if (isset($_POST['precio_hora']) && isset($_POST['precio_estadia']) && isset($_POST['precio_mensual'])) {
                            $precio = new Precio($_POST['precio_hora'], $_POST['precio_estadia'], $_POST['precio_mensual']);
                            Precio::SavePrecios($precio, ARCHIVO_PRECIOS);
                        } else echo "<br>Parametros insuficientes/Invalidos.<br>";
                    }else echo "<br>Solo valido para admins.<br>";
                }
                break;


            case '/ingreso':
                /* 4. (POST) ingreso: Sólo users. Se ingresara patente, fecha_ingreso (dia y hora), tipo (hora, estadia, mensual)
y el email del usuario que ingresó el auto y se guardará en el archivo autos.xxx. */
                if (isset($_SERVER['HTTP_TOKEN'])) {
                    $decoded = Login::ValidarJwt($_SERVER['HTTP_TOKEN']);
                    if ($decoded["type"] == 'user') {
                        if (isset($_POST['patente']) && ($_POST['tipo'] == "mensual" || $_POST['tipo'] == "estadia" || $_POST['tipo'] == "hora")) {
                            $ingreso = new Ingreso($_POST['patente'], $_POST['tipo']);
                            Ingreso::SaveIngresos($ingreso, ARCHIVO_INGRESOS);
                        } else echo "<br>Parametros insuficientes/Invalidos.<br>";
                    }else echo "<br>Solo valido para users.<br>";
                }
                break;


            case '/asignacion':
                /* 5. (POST) asignacion: Recibe legajo del profesor, id de la materia y turno (manana o noche) y lo guarda en el
archivo materias-profesores. No se debe poder asignar el mismo legajo en el mismo turno y materia. */
                if (isset($_SERVER['HTTP_TOKEN']) && Login::ValidarJwt($_SERVER['HTTP_TOKEN'])) {
                    if (isset($_POST['legajo']) && isset($_POST['id_materia']) && isset($_POST['turno'])) {
                        $arrayOfAsignaciones = Asignacion::CreateArrayOfAsignaciones(ARCHIVO_ASIGNACIONES);
                        $newAsig = new Asignacion($_POST['legajo'], $_POST['id_materia'], $_POST['turno']);
                        if (Asignacion::ValidarAsignacion($newAsig, $arrayOfAsignaciones)) {
                            array_push($arrayOfAsignaciones, $newAsig);
                            Asignacion::SaveAsignaciones($arrayOfAsignaciones, ARCHIVO_ASIGNACIONES);
                        } else echo "<br>No se puede volver a asignar el mismo legajo en el mismo turno y materia.<br>";
                    } else echo "<br>Parametros insuficientes/Invalidos.<br>";
                }
                break;
            default:
                echo "<br>Path info inexistente<br>";
        }
        break;
    case 'GET': //LISTAR RECURSOS
        switch ($path_info) {


            case '/materia':
                /* 6. (GET) materia: Muestra un listado con todas las materias. */
                if (isset($_SERVER['HTTP_TOKEN']) && Login::ValidarJwt($_SERVER['HTTP_TOKEN'])) {
                    File::MostrarArchivo(ARCHIVO_MATERIAS);
                }
                break;


            case '/profesor':
                /* 7. (GET) profesor: Muestra un listado con todas las profesores. */
                if (isset($_SERVER['HTTP_TOKEN']) && Login::ValidarJwt($_SERVER['HTTP_TOKEN'])) {
                    File::MostrarArchivo(ARCHIVO_PROFESORES);
                }
                break;


            case '/asignacion':
                /* File::MostrarArchivo(ARCHIVO_MATERIAS); */
                if (isset($_SERVER['HTTP_TOKEN']) && Login::ValidarJwt($_SERVER['HTTP_TOKEN'])) {
                    File::MostrarArchivo(ARCHIVO_ASIGNACIONES);
                }
                break;

            default:
                echo "<br>Path info inexistente<br>";
        }
        break;
    default:
        echo "<br/>sE iNGRESO POR DEFAULT";
}
