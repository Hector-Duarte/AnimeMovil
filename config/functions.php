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

//obtener IP de usuario
function getUserIp(){
if (isset($_SERVER["HTTP_CF_CONNECTING_IP"])) {
 return $_SERVER["HTTP_CF_CONNECTING_IP"]; //usar la ip obtenida por cloudflare
}else{
 return $_SERVER['REMOTE_ADDR']; //usar la ip optenida por php (si no se usa cloudflare como cdn)
}
} //fin de getUserIp


//generador de hash
function generateHash($input){
      $_arraysign = array();
      $_arraysign[] = $input; //entrada de data
      $_arraysign[] = $_SERVER['HTTP_USER_AGENT']; //agente de usuario del navegador
      $_arraysign[] = getUserIp(); //IP del usuario
      $_arraysign[] = SIGNATURE_HASH; //key hash

      $_str2sign = implode("\n", $_arraysign);

      return base64_encode( hash_hmac('sha256', urldecode(utf8_encode($_str2sign)), base64_decode(SIGNATURE_HASH), true) ); //hash para retornar
}
//fin generador de hash







//crear session
function createSession($usuario, $password){
/* Esta función solo se usara en la api por lo que no necesita diferentes tipos de callbaks */

  //obtener SALT de db
  $mysqli = new mysqli(HOST, USER, PASSWORD, DATABASE);

      $prep_stmt = "SELECT password, salt, level, user, id from usuarios WHERE user=? LIMIT 1;";
      $stmt = $mysqli->prepare($prep_stmt);
      $stmt->bind_param('s', $usuario);
      $stmt->execute();
      $stmt->store_result();


  // Obtiene las variables del resultado.
          $stmt->bind_result($db_password, $db_salt, $session_user_level, $session_user_name, $session_user_id);

          //verificar si la consulta retorno usuario
          if( !$stmt->fetch() ){
            error('No existe el usuario, verifica tu información.', 403); //el usuario no es existe
          }






  // Crea un hash con la contrasena y el salt.
  $password = hash('sha512', $password . $db_salt);

         // comprobar autenticacion de contraseñas
              if ( $password!=$db_password ) {

                       //contrasena incorrecta
                       error('Verifica tu contraseña.', 403);

              }else{

         //destruir passwords
         unset($password);
         unset($db_password);
         unset($db_salt);

         //procotolo para crear la sesion
         $token_expire = time() + 1296000; //expira en 15 dias
         $token_access = generateHash($session_user_id.$session_user_name.$session_user_level.$token_expire); //token hash de acceso para la sesión

         //insertar datos en la tabla de sessiones
         $prep_stmt = "INSERT INTO sessions(user_id, user_level, ip, token, expire) VALUES (?,?,?,?,?);";
         $stmt = $mysqli->prepare($prep_stmt);
         $stmt->bind_param('iissi', $session_user_id, $session_user_level, getUserIp(), $token_access, $token_expire);
         $stmt->execute();

         $session_id = $stmt->insert_id; //ID de la session

         $hash_check = generateHash($session_id.$token_access);


         respuesta_ok( array( "auth" => "$session_id:$token_access:$hash_check", "expire" => $token_expire, "expire_in" => 1296000  ) , 201); //retornar la id generada y terminar function

       } //fin de else

       /* cerrar conexion */
       $mysqli->close();
} //fin de createSession

//verificar session
function checkSession($session_id, $callback){
  /* esta functión se encargara de revisar que la sessión del usuario sea valida.
   callcabs: API responde en formato json, PAGUE retorna la variable definida "USER_SESSION" como false.
  */

         switch ($callback) {
           case 'API': //para la api
             error('session no valida.', 403); //error, aborta toda la solicitud
             break;

          case 'PAGUE': //para las paginas
             define('SESSION_STATUS', false); //la session la regresa como false, pero el proceso sigue.
             break;

           default: //algo esta mal, el callback no es valido.
             error('El callback para la verificación de la session no es valido.', 500); //algo esta mal. detiene toda la solicitud por seguridad.
             break;
         }



       //validar sesion
       ini_set('session.use_cookies', 0); //evitar que se envie una cookie en automatico
       session_name("session_id"); //cambiar el nombre de la session
       session_id($session_id); //asignar la id pasada para la session
       session_start(); //iniciar session

       //obtener la ip del usuario
       $session_IP = getUserIp();

      //validar por IP
      if($session_IP === $_SESSION['session_ip']){ //la session es valida
        define('SESSION_STATUS', true); //definir true ya que la session es valida.
      }else{ //la session no es valida.

        switch ($callback) {
          case 'API': //para la api
            error('session_id no valida.', 403); //error, aborta toda la solicitud
            break;

         case 'PAGUE': //para las paginas
            define('SESSION_STATUS', false); //la session la regresa como false, pero el proceso sigue.
            break;

          default: //algo esta mal, el callback no es valido.
            error('El callback para la verificación de la session_id no es valido.', 500); //algo esta mal. detiene toda la solicitud por seguridad.
            break;
        }//fin de switch

      }//fin de else (validar ip)

}
