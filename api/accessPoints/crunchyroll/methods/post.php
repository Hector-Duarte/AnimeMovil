<?php


//conexion a bd
$db= mysqli_connect('localhost', $sql_user, $sql_password, 'animemovil');
mysqli_set_charset($db,'utf8');

$resource=mysqli_real_escape_string($db,$resource);

//entrada de datos
$input = json_decode(file_get_contents('php://input'));


if($input->crunchyrollId AND $input->quality AND $input->nodeId){

$crunchyroll_id=mysqli_real_escape_string($db,$input->crunchyrollId);
$crunchyroll_quality=mysqli_real_escape_string($db,$input->quality);
$crunchyroll_nodeId=mysqli_real_escape_string($db,$input->nodeId);




//query a ejecutar
$query="INSERT INTO crunchyroll (status, crunchyrollId, quality, nodeId) VALUES ('0', '".$crunchyroll_id."', '".$crunchyroll_quality."', '".$crunchyroll_nodeId."');";





      //ejecutar consulta
      if(!($result=mysqli_query($db,$query))){
      http_response_code(400);
      $outinput->Message="Error al insertar nueva data";
      }else{ http_response_code(201); $outinput->status=true; }






}else{
      http_response_code(400);
      $outinput->Message="Error al insertar nueva data";

}





//cerrar SQL
mysqli_close($db);