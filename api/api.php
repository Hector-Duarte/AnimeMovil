<?php



require_once("funciones.php"); //funciones y variables


if( file_exists("nodes/". $_GET["node"] . "/api.php") ){
    require_once("nodes/". $_GET["node"] . "/api.php");
}else{
     //nodo no existe
     error("Nodo no existente", 404);
}
