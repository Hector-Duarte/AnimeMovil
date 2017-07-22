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
            error('No existe el usuario, verifica tu información.', 403); //el usuario no es valido
          }


  /* cerrar conexion */
  $mysqli->close();



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

         //iniciar sesion
         ini_set('session.use_cookies', 0); //evitar que se envie una cookie en automatico
         session_name("session_id"); //cambiar el nombre de la session
         session_start([
           'cookie_lifetime' => 1209600,
         ]);
         $session_id = session_id();
         $session_expire = time()+1209600; //expira en 14 dias la session

        //obtener la ip del usuario
        if (isset($_SERVER["HTTP_CF_CONNECTING_IP"])) {
         $session_IP = $_SERVER["HTTP_CF_CONNECTING_IP"]; //usar la ip obtenida por cloudflare
       }else{
         $session_IP = $_SERVER['REMOTE_ADDR']; //usar la ip optenida por php (si no se usa cloudflare como cdn)
       }

        //guardar información del usuario en la sesion
        $_SESSION['user_id'] = $session_user_id; //asignar el id del usuario
        $_SESSION['user_name'] = $session_user_name; //asignar el username del usuario
        $_SESSION['user_level'] = $session_user_level; //asignar el nivel del usuario (0 es admin y 1 es usuario normal)
        $_SESSION['session_expire'] = $session_expire; //cuando expira la session (14 dias)
        $_SESSION['session_ip'] = $session_IP; //la IP para authenticar que es dueño de la cookie el usuario.

         respuesta_ok( array( "id" => $session_id, "ip" => $session_IP, "lever" => $session_user_level, "expire" => date('m-d-Y H:i:s', $session_expire )  ) , 201); //retornar la id generada y terminar function

       } //fin de else
} //fin de createSession

//verificar session
function checkSession($session_id, $callback){
  /* esta functión se encargara de revisar que la sessión del usuario sea valida.
   callcabs: API responde en formato json, PAGUE retorna la variable definida "USER_SESSION" como false.
  */
       //filtrar session_id
       $session_id = preg_replace("/[^A-Za-z0-9\-]/", "", $session_id);
       if( !isset($session_id) ){ //si la session_id no es valida
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
         }
       }


       //validar sesion
       ini_set('session.use_cookies', 0); //evitar que se envie una cookie en automatico
       session_name("session_id"); //cambiar el nombre de la session
       session_id($session_id); //asignar la id pasada para la session
       session_start(); //iniciar session
       
       //obtener la ip del usuario
       if (isset($_SERVER["HTTP_CF_CONNECTING_IP"])) {
        $session_IP = $_SERVER["HTTP_CF_CONNECTING_IP"]; //usar la ip obtenida por cloudflare
      }else{
        $session_IP = $_SERVER['REMOTE_ADDR']; //usar la ip optenida por php (si no se usa cloudflare como cdn)
      }

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
