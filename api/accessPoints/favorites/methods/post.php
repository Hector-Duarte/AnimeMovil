<?php


//conexion a bd
$db= mysqli_connect('localhost', $sql_user, $sql_password, 'animemovil');
mysqli_set_charset($db,'utf8');

$resource=mysqli_real_escape_string($db,$resource);
$userId=mysqli_real_escape_string($db,$_COOKIE["userId"]);
$userMd5=mysqli_real_escape_string($db,$_COOKIE["userMd5"]);

if(is_numeric($resource) AND $resource AND $userId AND $userMd5){

//query a ejecutar
$query="INSERT INTO favoritos (id, nodeId)
SELECT a.id, b.id 
FROM usuarios as a, nodes as b
WHERE a.id='".$userId."' AND a.password='".$userMd5."' AND b.id='".$resource."' AND b.kind='ANIME' LIMIT 1;";





      //ejecutar consulta
      if(!($result=mysqli_query($db,$query))){
      http_response_code(400);
      $outinput->Message="Error al insertar nueva data";
      }else{ http_response_code(201); $outinput->status=true; }








}else{
      //intento de hackeo
      http_response_code(400);
      $outinput->Message="Solicitud invalida";

}





//cerrar SQL
mysqli_close($db);