<?php

/* Funciones basica para todo el sitio */



function error($mensaje, $code){
$json = array("status" => false, "error" => $mensaje); //respuesta
http_response_code($code); //respuesta error
echo json_encode($json, JSON_PRETTY_PRINT);
exit();
}



function verificarAPIKEY(){
$key = filter_input(INPUT_GET, 'key', FILTER_SANITIZE_STRING);
if(KEYAPI != $key){
error("Key invalida", 403);
}
}


function respuesta_ok($data, $code){

http_response_code($code); //respuesta http

$output->status = true;
$output->result = $data;
echo json_encode($output, JSON_PRETTY_PRINT);
exit();
}




function verificarCache($cacheFile){
return file_exists( CACHE_PATH . $cacheFile);
}

function putCache($cacheFile, $cacheContent){
file_put_contents( CACHE_PATH . $cacheFile, $cacheContent);
return true;
}

function getCache($cacheFile){
return file_get_contents( CACHE_PATH . $cacheFile);
}








function validateSession(){

//comprobar si existe hash
if($_COOKIE["session_hash"]){

   $_arraysign = array();
   $_arraysign[] = $_COOKIE["session_user_id"]; //id del usuario
   $_arraysign[] = $_COOKIE["session_user_name"]; //nombre de usuario
   $_arraysign[] = $_COOKIE["session_id"]; //session id
   $_arraysign[] = $_COOKIE["session_user_level"]; //nivel de usuario (0=admin && 1=usuario estandar)
   $_arraysign[] = $_COOKIE["session_expire"]; //expiracion
   $_arraysign[] = SIGNATURE_HASH_USER; //key hash

   $_str2sign = implode("\n", $_arraysign);

   $session_hash = base64_encode( hash_hmac('sha256', urldecode(utf8_encode($_str2sign)), base64_decode($key), true) ); //hast token para verificar sesion


          if($session_hash === $_COOKIE["session_hash"] AND is_numeric($_COOKIE["session_expire"]) AND $_COOKIE["session_expire"] > time() ){
          return true;
           }else{
          error("Error de session",403);
          return false;

           }
}else{

          error("Error de session",403);
          return false;

     }
}
