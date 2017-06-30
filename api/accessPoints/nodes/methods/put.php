<?php

//conexion a bd
$db= mysqli_connect('localhost', $sql_user, $sql_password, 'animemovil');
mysqli_set_charset($db,'utf8');


//escapear
$resource=mysqli_real_escape_string($db,$resource);


//obtener data antigua
$query="SELECT * FROM nodes WHERE id='".$resource."' LIMIT 1;";

//ejecutar consulta
$result =mysqli_query($db,$query);

$outinput->old=mysqli_fetch_object($result);





if($resource){
//entrada de datos
$input=json_decode(file_get_contents('php://input'));


//evaluar estructura de entrada
if(!$input){


  //json input invalido
  http_response_code(400);
  $outinput->Message="estructura BODY invalida";

}


//preparar query SET


if($input->status){
//status
mysqli_query($db,"UPDATE nodes SET status='".$input->status."' WHERE id='".$resource."' LIMIT 1;");
}

if($input->title){
//title
mysqli_query($db,"UPDATE nodes SET title='".$input->title."' WHERE id='".$resource."' LIMIT 1;");
}

if($input->idCap){
//idCap
mysqli_query($db,"UPDATE nodes SET idCap='".$input->idCap."' WHERE id='".$resource."' LIMIT 1;");
}

if($input->slug){
//slug
mysqli_query($db,"UPDATE nodes SET slug='".$input->slug."' WHERE id='".$resource."' LIMIT 1;");
}

if($input->kind){
//kind
mysqli_query($db,"UPDATE nodes SET kind='".$input->kind."' WHERE id='".$resource."' LIMIT 1;");
}

if($input->parent){
//parent
mysqli_query($db,"UPDATE nodes SET parent='".$input->parent."' WHERE id='".$resource."' LIMIT 1;");
}


if($input->simulcasts){
//simulcasts
mysqli_query($db,"UPDATE nodes SET simulcasts='".$input->simulcasts."' WHERE id='".$resource."' LIMIT 1;");
}



if($input->imgCap){
//imgCap
mysqli_query($db,"UPDATE nodes SET imgCap='".$input->imgCap."' WHERE id='".$resource."' LIMIT 1;");
}



if($input->imgPor){
//imgPor
mysqli_query($db,"UPDATE nodes SET imgPor='".$input->imgPor."' WHERE id='".$resource."' LIMIT 1;");
}



if($input->wallpaper){
//wallpaper
mysqli_query($db,"UPDATE nodes SET wallpaper='".$input->wallpaper."' WHERE id='".$resource."' LIMIT 1;");
}


if($input->sinopsis){
//sinopsis
mysqli_query($db,"UPDATE nodes SET sinopsis='".$input->sinopsis."' WHERE id='".$resource."' LIMIT 1;");
}



if($input->emision){
//emision
mysqli_query($db,"UPDATE nodes SET emision='".$input->emision."' WHERE id='".$resource."' LIMIT 1;");
}


//FIN preparar query SET



//consultar nueva info

//query a ejecutar
$query="SELECT * FROM nodes WHERE id='".$resource."' LIMIT 1;";

//ejecutar consulta
$result =mysqli_query($db,$query);

$outinput->new=mysqli_fetch_object($result);






//fin de recurso existente
}else{
      //intento de hackeo
      http_response_code(400);
      $outinput->Message="Solicitud invalida especifique recurso - nodes/{id}";

}


//cerrar SQL
mysqli_close($db);