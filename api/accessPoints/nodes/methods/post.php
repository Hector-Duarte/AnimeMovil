<?php

//conexion a bd
$db= mysqli_connect('localhost', $sql_user, $sql_password, 'animemovil');
mysqli_set_charset($db,'utf8');


//escapear
$resource=mysqli_real_escape_string($db,$resource);



if(!$resource){

//entrada de datos
$input = json_decode(file_get_contents('php://input'));



//evaluar estructura de entrada
if(!$input){


  //json input invalido
  http_response_code(400);
  $outinput->Message="estructura BODY invalida";

}else if($input->status!="AVALIABLE" AND $input->status!="PENDING"){


  //status no valido
  http_response_code(400);
  $outinput->Message="status invalido";

}else if(!$input->title){


  //status no valido
  http_response_code(400);
  $outinput->Message="title invalido";

}else if(!$input->idCap AND $input->kind=="EPISODIO"){


  //idCap no valido
  http_response_code(400);
  $outinput->Message="idCap invalido";

}else if($input->idCap AND $input->kind=="ANIME"){


  //idCap no valido para kind:ANIME
  http_response_code(400);
  $outinput->Message="idCap no necesario para kind:ANIME";

}else if(!$input->slug || strpos($input->slug," ") ){


  //idCap no valido
  http_response_code(400);
  $outinput->Message="slug invalido";

}else if(!$input->kind || strpos($input->kind," ") ){


  //kind no valido
  http_response_code(400);
  $outinput->Message="kind invalido";

}else if($input->kind=="ANIME" AND $input->parent){


  //parent no valido
  http_response_code(400);
  $outinput->Message="parent no requerido para kind:ANIME";

}else if($input->kind=="EPISODIO" AND !$input->parent){

  //parent no valido
  http_response_code(400);
  $outinput->Message="parent requerido para kind:EPISODIO";

}else if($input->simulcasts!=1 AND $input->simulcasts!=0){

  //parent no valido
  http_response_code(400);
  $outinput->Message="simulcasts (1/0), no permitido null";

}else if($input->wallpaper AND $input->kind=="EPISODIO"){

  //parent no valido
  http_response_code(400);
  $outinput->Message="Imagen wallpaper no valida para kind:EPISODIO";

}else if($input->imgPor AND $input->kind=="EPISODIO"){

  //parent no valido
  http_response_code(400);
  $outinput->Message="Imagen portada no valida para kind:EPISODIO";

}else if(!$input->wallpaper AND $input->kind=="ANIME"){

  //parent no valido
  http_response_code(400);
  $outinput->Message="Imagen wallpaper requerida";

}else if(!$input->imgPor AND $input->kind=="ANIME"){

  //parent no valido
  http_response_code(400);
  $outinput->Message="Imagen portada requerida";

}else if($input->kind=="ANIME" AND !$input->emision){

  //parent no valido
  http_response_code(400);
  $outinput->Message="Emision requerida";

}else if($input->kind=="EPISODIO" AND $input->emision){

  //parent no valido
  http_response_code(400);
  $outinput->Message="Emision no requerida";

}else if($input->kind=="ANIME" AND !$input->sinopsis){

  //parent no valido
  http_response_code(400);
  $outinput->Message="Sinopsis requerida";

}else if($input->kind=="EPISODIO" AND $input->sinopsis){

  //parent no valido
  http_response_code(400);
  $outinput->Message="Sinopsis no requerida";

}else{
//salida de datos


    
    switch ($input->kind){

  case 'EPISODIO':

      //VARS
      $status=mysqli_real_escape_string($db,$input->status);
      $title=mysqli_real_escape_string($db,$input->title);
      $idCap=mysqli_real_escape_string($db,$input->idCap);
      $slug=mysqli_real_escape_string($db,$input->slug);
      $kind=mysqli_real_escape_string($db,$input->kind);
      $parent=mysqli_real_escape_string($db,$input->parent);
      $simulcasts=mysqli_real_escape_string($db,$input->simulcasts);


      //query a ejecutar
      $query="INSERT INTO nodes (status, title, idCap, slug, kind, parent, simulcasts) VALUES ('".$status."', '".$title."', ".$idCap.",  '".$slug."', '".$kind."', '".$parent."', '".$simulcasts."');";

      //ejecutar consulta
      if(!($result=mysqli_query($db,$query))){
      http_response_code(400);
      $outinput->Message="Error al insertar nueva data";
      }else{ http_response_code(201); $outinput->nodeId=mysqli_insert_id($db); }
     break;

  case 'ANIME':

      //Generos
      $genders_count=count($input->genders);

//abortar
if($genders_count==0 || $genders_count > 21){ http_response_code(400);$outinput->Message="Generos invalidos";  }else{





      //VARS
      $status=mysqli_real_escape_string($db,$input->status);
      $title=mysqli_real_escape_string($db,$input->title);
      $slug=mysqli_real_escape_string($db,$input->slug);
      $kind=mysqli_real_escape_string($db,$input->kind);
      $imgPor=mysqli_real_escape_string($db,$input->imgPor);
      $wallpaper=mysqli_real_escape_string($db,$input->wallpaper);
      $simulcasts=mysqli_real_escape_string($db,$input->simulcasts);
      $emision=mysqli_real_escape_string($db,$input->emision);
      $sinopsis=mysqli_real_escape_string($db,$input->sinopsis);

      //query a ejecutar
      $query="INSERT INTO nodes (status, title, slug, kind, imgPor, wallpaper, simulcasts, emision, sinopsis) VALUES ('".$status."', '".$title."',  '".$slug."', '".$kind."', '".$imgPor."', '".$wallpaper."', '".$simulcasts."', '".$emision."', '".$sinopsis."');";

      //ejecutar consulta
      if(!($result=mysqli_query($db,$query))){
      http_response_code(400);
      $outinput->Message="Error al insertar nueva data";
      }else{ 
            http_response_code(201); 
            $nodeId=mysqli_insert_id($db);
            $outinput->nodeId=$nodeId;


//asginar generos
if($nodeId){


$num=0;
//iniciar ids string
$genders_ids="";

//asignar todas las ids de generos a nodeId
while($num<$genders_count){

$genders_ids.="('".$nodeId."','".mysqli_real_escape_string($db,$input->genders[$num])."')";
if( ($num+1) < $genders_count ){ $genders_ids=$genders_ids.","; }
$num+=1;
}

$genders="INSERT INTO generos (nodeId,id) VALUES  ".$genders_ids."  ;";
$result=mysqli_query($db,$genders);

}



          }

} //fin de comprobar generos
     break;


  default:
     http_response_code(405);
     $outinput->Message="kind no soportado";

    }






         
}         
         



}else{
      //intento de hackeo
      http_response_code(400);
      $outinput->Message="Solicitud invalida, no especifique recurso - nodes/{id}";

}


//cerrar SQL
mysqli_close($db);