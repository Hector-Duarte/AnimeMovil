<?php


//conexion a bd
$db= mysqli_connect('localhost', $sql_user, $sql_password, 'animemovil');
mysqli_set_charset($db,'utf8');

$resource=mysqli_real_escape_string($db,$resource);
$userId=mysqli_real_escape_string($db,$_COOKIE["userId"]);

if(!$resource AND $userId){

//query a ejecutar
$query="
SELECT DISTINCT  b.nodeId as id, a.title, a.slug, a.wallpaper
FROM nodes as a, favoritos as b
WHERE a.id=b.nodeId AND b.id='".$userId."' AND a.status='AVALIABLE' LIMIT 20;";

$result=mysqli_query($db,$query);


      //aginar todo a nodes para su salida
      while($sql_object = mysqli_fetch_object($result)){

      $outinput->nodes[]=$sql_object;

      }
      //contar todos los nodos resultantes
      $outinput->count=count($outinput->nodes);

      $outinput->limit=20;







}else if($resource AND $userId){



//query a ejecutar
$query="
SELECT DISTINCT  b.nodeId as id, a.title, a.slug, a.wallpaper
FROM nodes as a, favoritos as b
WHERE a.id=b.nodeId AND b.nodeId='".$resource."' AND b.id='".$userId."' AND a.status='AVALIABLE' LIMIT 1;";

$result=mysqli_query($db,$query);


      //aginar todo a nodes para su salida
      if( $outinput->nodes[]=mysqli_fetch_object($result) ){
      $outinput->status=true;
      }else{
      $outinput->status=false;
      }





}else{
      //intento de hackeo
      http_response_code(400);
      $outinput->Message="Solicitud invalida";

}





//cerrar SQL
mysqli_close($db);