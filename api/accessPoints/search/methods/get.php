<?php


//conexion a bd
$db= mysqli_connect('localhost', $sql_user, $sql_password, 'animemovil');
mysqli_set_charset($db,'utf8');


//escapear
$resource=mysqli_real_escape_string($db,$resource);
$value=mysqli_real_escape_string($db,$value);

$offset="";
if(is_numeric($value)){
$offset=" OFFSET ".$value." ";
}

if($resource=="simulcasts"){

      //query a ejecutar
      $query="SELECT id,status,title,idCap,slug,kind,parent,imgCap,simulcasts FROM nodes WHERE kind='EPISODIO' AND simulcasts=1 AND status='AVALIABLE' LIMIT 10 ".$offset.";";

      //ejecutar consulta
      $result =mysqli_query($db,$query);
     
      //aginar todo a nodes para su salida
      while($sql_object = mysqli_fetch_object($result)){

      $outinput->nodes[]=$sql_object;

      }
      //contar todos los nodos resultantes
      $outinput->count=count($outinput->nodes);



}else{
      //intento de hackeo
      http_response_code(400);
      $outinput->Message="Recurso invalido";

}


//cerrar SQL
mysqli_close($db);