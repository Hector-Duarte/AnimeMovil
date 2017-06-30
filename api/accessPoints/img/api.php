<?php

//recurso existe parseado

    switch ($method){

  case 'POST':
     require_once("methods/post.php");
     break;

  case 'PUT':
     require_once("methods/put.php");
     break;

  default:
     http_response_code(405);
     $outinput->Message="Metodo no soportado";

    }

