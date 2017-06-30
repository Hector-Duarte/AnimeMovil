<?php

//validar api key
verificarAPIKEY();


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