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


/* FUNCTIONS EXPERIMENTAS */


//crear session
function createSession($usuario, $password){

  //obtener SALT de db
  $mysqli = new mysqli(HOST, USER, PASSWORD, DATABASE);

      $prep_stmt = "SELECT password, salt, level, user, id from usuarios WHERE user=? LIMIT 1;";
      $stmt = $mysqli->prepare($prep_stmt);
      $stmt->bind_param('s', $usuario);
      $stmt->execute();
      $stmt->store_result();


  // Obtiene las variables del resultado.
          $stmt->bind_result($db_password, $db_salt, $session_user_level, $session_user_name, $session_user_id);
          $stmt->fetch();
  /* cerrar conexion */
  $mysqli->close();


  // Crea un hash con la contrasena y el salt.
  $password = hash('sha512', $password . $db_salt);





         // comprobar autenticacion
              if ( $password!=$db_password ) {

                       //contrasena incorrecta
                       error('Verifica tu información.', 403);

              }else{

         //destruir passwords
         unset($password);
         unset($db_password);
         unset($db_salt);

         //iniciar sesion
         session_start([
           'cookie_lifetime' => 604800,
         ]);
         $session_id=session_id();
                         $_arraysign = array();
                          $_arraysign[] = $session_user_id; //id del usuario
                          $_arraysign[] = $session_user_name; //nombre de usuario
                          $_arraysign[] = $session_id; //session id
                          $_arraysign[] = $session_user_level; //nivel de usuario (0=admin && 1=usuario estandar)
                          $_arraysign[] = SIGNATURE_HASH_USER; //key hash

                          $_str2sign = implode("\n", $_arraysign);

                         $session_hash = base64_encode( hash_hmac('sha256', urldecode(utf8_encode($_str2sign)), base64_decode($key), true) ); //hast token para verificar sesion



         respuesta_ok( array( "id" => $session_id, "auth" => $session_hash, "expire" => date('m/d/Y', time()+604800 )  ) , 201); //retornar la id generada y terminar function
       } //fin de else
} //fin de createSession
