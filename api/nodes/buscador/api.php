<?php

switch ($_SERVER["REQUEST_METHOD"]) {
    case "GET":
                 require_once("methods/GET.php");

        break;
      default:
                 error("Metodo no soportado", 405);

}