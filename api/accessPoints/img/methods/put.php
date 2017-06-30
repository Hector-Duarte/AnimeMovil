<?php

//recibir imagen temporalmente
$image= file_get_contents('php://input');



$path=realpath(__DIR__ . '/../../../../assets/media/')."/";
$fileName=$resource."_";


//aplicar cambios segun el tipo de imagen
    switch ($value){

  case 'portada':
     $fileName.="portada";
     break;

  case 'diminuto':
     $fileName.="diminuto";
     break;

  case 'completo':
     $fileName.="completo";
     break;

  case 'episodio':
     $fileName.="episodio";
     break;

  default:
     http_response_code(400);
     $outinput->Message="nodo no existente";

    }


$fileName.=".jpg";
$path=$path.$fileName;



//salida
if(file_put_contents($path,$image)){
$outinput->status=true;
}else{
$outinput->status=false;
}

$outinput->name=$fileName;







