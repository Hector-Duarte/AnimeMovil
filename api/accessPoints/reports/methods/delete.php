<?php


//conexion a bd
$db= mysqli_connect('localhost', $sql_user, $sql_password, 'animemovil');
mysqli_set_charset($db,'utf8');


//escapear
$resource=mysqli_real_escape_string($db,$resource);



switch ($resource){

    case '':

      http_response_code(400);
      $outinput->Message="nodeId invalido /api/reports/{id}"; 


    break;

    default:

      //query a ejecutar
      $query="DELETE FROM reports WHERE id='".$resource."' LIMIT 1;";

      //ejecutar consulta
      mysqli_query($db,$query);
      $outinput->Message="nodeId borrando con exito"; 

}



//cerrar SQL
mysqli_close($db);