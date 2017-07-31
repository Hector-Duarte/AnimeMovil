<?php

//json de salida global
$output = array();

//content header
header("Content-Type: application/json");



require_once(__DIR__ . "/../config/config.php"); //configuraciones
require_once(__DIR__ . "/../config/functions.php"); //funciones globales
require_once('funtions.php'); //funciones de la API


if( file_exists("nodes/". $_GET["node"] . "/api.php") ){
    require_once("nodes/". $_GET["node"] . "/api.php");
}else{
     //nodo no existe
     error("Nodo no existente", 404);
}
