<?php

//validar api key
verificarAPIKEY();


switch ($_SERVER["REQUEST_METHOD"]) {
    case "GET":
                 if( is_numeric($_GET["value"]) ){
                  require_once("actions/getStream.php"); //obtener valores del stream
                  }else{
                  include_once("actions/servers.php"); //obtener pendientes de subir segun el nodo
                  }
        break;

            case "POST":
             include_once("actions/servers.php"); //para agregar al nodo
           break;




      default:
                 error("Metodo no soportado", 405);

}