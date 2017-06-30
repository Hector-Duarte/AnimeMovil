<?php

//conexion a bd
$db= mysqli_connect('localhost', $sql_user, $sql_password, 'animemovil');
mysqli_set_charset($db,'utf8');


//escapear
$resource=mysqli_real_escape_string($db,$resource);
$value=mysqli_real_escape_string($db,$value);


if(is_numeric($resource) AND !$value){

      //query a ejecutar
      $query="SELECT * FROM nodes WHERE id='".$resource."' LIMIT 1";

      //ejecutar consulta
      $result =mysqli_query($db,$query);

      $data=mysqli_fetch_object($result);

          switch ($data->kind){

          case 'EPISODIO':
          $outinput->id=$data->id;
          $outinput->status=$data->status;
          $outinput->title=$data->title;
          $outinput->idCap=$data->idCap;
          $outinput->slug=$data->slug;
          $outinput->kind=$data->kind;
          $outinput->parent=$data->parent;
          $outinput->simulcasts=$data->simulcasts;
          $outinput->imgCap=$data->imgCap;

          break;


          case 'ANIME':
          $outinput->id=$data->id;
          $outinput->status=$data->status;
          $outinput->title=$data->title;
          $outinput->idCap=$data->idCap;
          $outinput->slug=$data->slug;
          $outinput->kind=$data->kind;
          $outinput->parent=$data->parent;
          $outinput->simulcasts=$data->simulcasts;
          $outinput->imgPor=$data->imgPor;
          $outinput->wallpaper=$data->wallpaper;
          break;

          }
         
         

}else if(is_numeric($resource) AND $value=="children"){

      //query a ejecutar
      $query="SELECT id,status,title,idCap,slug,kind,parent,imgCap FROM nodes WHERE kind='EPISODIO' AND status='AVALIABLE' AND parent='".$resource."' LIMIT 1000";

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