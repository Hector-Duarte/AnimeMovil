<?php


//conexion a bd
$db= mysqli_connect('localhost', $sql_user, $sql_password, 'animemovil');
mysqli_set_charset($db,'utf8');


//escapear
$resource=mysqli_real_escape_string($db,$resource);



switch ($resource){


    case 'pending':

      //query a ejecutar
      $query="SELECT * FROM crunchyroll WHERE status=0 LIMIT 20;";

      //ejecutar consulta
      $result =mysqli_query($db,$query);
      while($sql_object = mysqli_fetch_object($result)){

      $outinput->nodes[]=$sql_object;

      }
      $outinput->count=count($outinput->nodes);


    break;



    case '':

      //query a ejecutar
      $query="SELECT * FROM crunchyroll WHERE status=1 LIMIT 20;";

      //ejecutar consulta
      $result =mysqli_query($db,$query);
      while($sql_object = mysqli_fetch_object($result)){

      $outinput->nodes[]=$sql_object;

      }
      $outinput->count=count($outinput->nodes);


    break;

    default:

      //query a ejecutar
      $query="SELECT * FROM crunchyroll WHERE id='".$resource."' LIMIT 1;";

      //ejecutar consulta
      $result =mysqli_query($db,$query);
      if($sql_object=mysqli_fetch_object($result)){
      $outinput=$sql_object;
      }else{ http_response_code(400);$outinput->Message="nodeId invalido"; }


}



//cerrar SQL
mysqli_close($db);