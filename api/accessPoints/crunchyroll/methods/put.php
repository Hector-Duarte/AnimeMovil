<?php

//conexion a bd
$db= mysqli_connect('localhost', $sql_user, $sql_password, 'animemovil');
mysqli_set_charset($db,'utf8');


//escapear
$resource=mysqli_real_escape_string($db,$resource);


//obtener data antigua
$query="SELECT * FROM crunchyroll WHERE id='".$resource."' LIMIT 1;";

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


if($input->idSubs AND $input->link){

mysqli_query($db,"UPDATE crunchyroll SET status='1', idSubs='".$input->idSubs."', link='".$input->link."' WHERE id='".$resource."' LIMIT 1;");
}


//FIN preparar query SET



//consultar nueva info

//query a ejecutar
$query="SELECT * FROM crunchyroll WHERE id='".$resource."' LIMIT 1;";

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