<?php

//conexion a bd
$db= mysqli_connect('localhost', $sql_user, $sql_password, 'animemovil');
mysqli_set_charset($db,'utf8');


//escapear
$resource=mysqli_real_escape_string($db,$resource);




if(is_numeric($resource)){

      //query a ejecutar
      $query="DELETE FROM nodes WHERE id='".$resource."' LIMIT 1";

      //ejecutar consulta
      if(mysqli_query($db,$query)){
      http_response_code(204);
      $outinput->Message="Recurso borrado con exito.";
      }else{
      http_response_code(400);
      $outinput->Message="Recurso no borrado (no existente o invalido)";
      }         

}else{
      //intento de hackeo
      http_response_code(400);
      $outinput->Message="Recurso invalido";

}


//cerrar SQL
mysqli_close($db);