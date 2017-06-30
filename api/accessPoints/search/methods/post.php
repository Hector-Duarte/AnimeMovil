<?php

//conexion a bd
$db= mysqli_connect('localhost', $sql_user, $sql_password, 'animemovil');
mysqli_set_charset($db,'utf8');


//entrada de datos
$input = json_decode(file_get_contents('php://input'));

//vars
$num=0;
$like=" AND ";
$offset="";

//LIKE query
if($input->query){
$num+=1;
$like_string=mysqli_real_escape_string($db,$input->query);
$like.=" (a.title like '%".$like_string."%') ";
}

//LIKE start
if($input->start){
if($num==1){ $like.=" AND "; $num-=1;}
$num+=1;
$like_string=mysqli_real_escape_string($db,$input->start);
$like.=" (a.title like '".$like_string."%') ";
}

//OFFSET
if($input->offset and is_numeric($input->offset)){
$offset=" OFFSET ".$input->offset." ";
}

//Generos
$genders_ids="";
if($input->genders){

$num=0;
$genders_count=count($input->genders);
//iniciar ids string
$genders_ids=" a.id=b.nodeId AND ";

//asignar todas las ids de generos a nodeId

while($num<$genders_count){

$genders_ids.=" b.id='".mysqli_real_escape_string($db,$input->genders[$num])."' ";
if( ($num+1) < $genders_count ){ $genders_ids=$genders_ids." OR "; }
$num+=1;
}
$genders_ids=$genders_ids." AND ";

}

//query
if($input->genders || $input->start || $input->query){


//query de busqueda
$query="

SELECT DISTINCT a.id, a.title, a.slug, a.imgPor, a.wallpaper, a.simulcasts, a.emision, a.sinopsis 
FROM nodes as a, generos as b
WHERE ".$genders_ids."  status='AVALIABLE' AND kind='ANIME' ".$like." LIMIT 10 ".$offset.";

";



$result =mysqli_query($db,$query);

//imprimir todas las respuestas
while($node=mysqli_fetch_object($result)){
$outinput->nodes[]=$node;
}

//contar todos los nodos de salida
$outinput->count=count($outinput->nodes);

//devolver respuesta si la solicitud es correcta pero no retorno data
if($outinput->count==0){ $outinput->Message="Sin resultados"; }


}else{
http_response_code(400);
$outinput->Message="Requerida al menos una sentencia";
}

//cerrar SQL
mysqli_close($db);