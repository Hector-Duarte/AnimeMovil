<?php

//recurso existe parseado

    switch ($method){

  case 'GET':
     require_once("methods/get.php");
     break;

  case 'POST':
     require_once("methods/post.php");
     break;

  case 'DELETE':
     require_once("methods/delete.php");
     break;

  case 'PUT':
     require_once("methods/put.php");
     break;

  default:
     http_response_code(405);
     $outinput->Message="Metodo no soportado";

    }

