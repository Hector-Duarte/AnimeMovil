<?php

//validar sesion
checkSession('API', false); //'API' es el tipo de callback y el false es que no es necesario que sea admin


switch ($_SERVER["REQUEST_METHOD"]) {
    case "GET":
                 require_once("methods/GET.php");

        break;
    case "POST":
                 require_once("methods/POST.php");

        break;
    case "DELETE":
                 require_once("methods/DELETE.php");

        break;
      default:
                 error("Metodo no soportado", 405);

}
