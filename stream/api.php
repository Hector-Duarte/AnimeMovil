<?php

//declarar cabeceras
header("Access-Control-Allow-Origin: *"); //acceso todo los origins
header("Cache-Control: no-cache, max-age=0"); //no permitir cache

//obtener valores
$id = $_GET["id"];
$expire = $_GET["expire"];
$node = $_GET["node"];
$callback = $_GET["callback"];


//funcion de error
function error($code, $message){
    header("Content-Type: application/json");
	http_response_code($code);
	echo json_encode( array('status' => false, 'message' => $message), JSON_PRETTY_PRINT);
	exit();
}


//generar firma
$_arraysign = array();
 $_arraysign[] = $id; //id del cap
 $_arraysign[] = $expire; //expira en 6 horas
$_arraysign[] = $callback; //tipo de callback

   //IP seguridad
  if (isset($_SERVER["HTTP_CF_CONNECTING_IP"])) {
   $_SERVER['REMOTE_ADDR'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
   }
  $_arraysign[] = $_SERVER['REMOTE_ADDR']; //Ip de usuario      
  //FIP IP seguridad

 $_arraysign[] = "bp2GGbE8wWkMU1wN81DAIbQRmZkGTxyMmiHIkf+7e1A="; //key hash
 $_str2sign = implode("\n", $_arraysign);
$signature = base64_encode(
 hash_hmac('sha256', urldecode(utf8_encode($_str2sign)), base64_decode($key), true)
 );



                                         //comprobar firma
                                         if( $signature != $_GET["signature"] || $expire < time() ){
                                         	//firma invalida 
                                             error(403, "Firma invalida");
                                         }


//obtener datos del stream
//comprobar si existe en cache
if( !file_exists("cache/".$id.".json") ){

//obtener desde api web
$streamInfo=file_get_contents('http://138.197.111.6/api/stream/'. $id .'?key=b62G8GbE8wWkMUwN8177ugjemiHIkf7e1A');

//codificar a array para su uso en el stream
$streamInfo=json_decode($streamInfo);


        //comprobar que la api regresa datos correctos
        if (!$streamInfo->status) {
        	//algo esta mal con la api
        	error(500, "Error al conectar con la api");
        }else{
        	//guardar en la cache
            file_put_contents("cache/".$id.".json", json_encode($streamInfo, JSON_PRETTY_PRINT) );
        }


}else{
//existe en cache, obtener desde la cache
$streamInfo=file_get_contents("cache/".$id.".json");

//codificar a array para su uso en el stream
$streamInfo=json_decode($streamInfo);

        //comprobar que la cache regresa datos correctos
        if (!$streamInfo->status) {
        	//algo esta mal con la cache
        	unlink("cache/".$id.".json"); //borar de la cache
        	error(500, "Error al conectar con la cache - Se ha purgado la cache de la id"); //retornar error
        }



}


          if($streamInfo->result->$node || $node == "akiba"){ //verificar si existe el nodo y permitir siempre akiba
                     //lanzar nodo
	                 include_once("nodes/". $node .".php"); 

                   //la api no tiene que llegar hasta aqui, así que retornar error de proceso
                   error(202, "Algo salio mal, la solicitud es valida pero el nodo no ha finalizado la solicitud");
             }else{
                error(501, "la api no contiene este nodo activo"); //retornar error
             }
