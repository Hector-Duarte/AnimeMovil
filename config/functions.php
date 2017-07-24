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



//verificar session
function checkSession($callback, $varify_admin){
  /* esta functión se encargara de revisar que la sessión del usuario sea valida.
   callcabs: API responde en formato json, PAGUE retorna la variable definida "USER_SESSION" como false.
  */
       //obtener el auth
       if($_COOKIE['auth']){
         $auth_token = $_COOKIE['auth']; //obtener el auth de las cookies
       }else if($_POST['auth']){
         $auth_token = $_POST['auth']; //obtener el auth de variable POST
       }else{
         $auth_token=false; //el auth no existe
       }


       //validar sesion
       if($auth_token){
         //el token existe, validar los hash para posteriormente abrir la conexión sql
         $auth_token = explode(':', $auth_token);
            if( count($auth_token) === 3 ){ //tienen que ser 3 para que se proceda a la validación de hashs
              //se retornaron 3 divisiones en total (id_session:hash_primary:hash_secundary)

              //resolver el hash_secundary
              $hash_check = generateHash($auth_token[0].$auth_token[1]); //regenerar el hash_secundary
              if($hash_check === $auth_token[2]){ //el hash generador de session_id y hash_primary tiene que dar como resultado el hash_secundary
                //el hash secundary fue resuelto correctamente (esto confirma que el session_id y el hash_primary no fueron alterados)
                //consultar el session_id en la tabla sessions

                //abrir sql
                $mysqli = new mysqli(HOST, USER, PASSWORD, DATABASE);

                    $prep_stmt = "SELECT user_id, user_level, ip, token, expire from sessions WHERE id=? LIMIT 1;";
                    $stmt = $mysqli->prepare($prep_stmt);
                    $stmt->bind_param('i', $auth_token[0]); //pasar la id de la session
                    $stmt->execute();
                    $stmt->store_result();


                        //Obtiene las variables del resultado.
                        $stmt->bind_result($user_id, $user_level, $ip, $token, $expire);

                        //verificar si la consulta retorno usuario
                        if( $stmt->fetch() ){
                          //la session se ha encontrado, proceder a validar
                          if($token === $auth_token[1] and $ip === getUserIp() and $expire > time() ){ //se valida todos los datos para autorizar session
                                  //la session es valida :D
                                  define('SESSION_STATUS', true); //definir session como valida como cierta

                                    //validar si es usuario normal | 0 es admin y 1 es usuario normal
                                    if($user_level === 1){//es usuario normal
                                        define('IS_ADMIN', false); //no es admin
                                    }else if($user_level === 0){// es admin
                                        define('IS_ADMIN', true); // ¡SI ES ADMIN!
                                    }else{//algo esta mal, detener todo
                                        error('Algo esta mal, limpia los datos de tu navegador y vuelve a iniciar sessión.', 500);
                                        //la funcion error detiene todo en automatico.
                                    }



                          }else{
                            //la validación no fue correcta.
                            define('SESSION_STATUS', false);
                            define('IS_ADMIN', false); //no es admin
                          }

                        }else{
                          //la session no existe
                          define('SESSION_STATUS', false);
                          define('IS_ADMIN', false); //no es admin
                        }



              }else{ //el hash_secundary no es valido, los datos id_session:hash_primary fueron alterados
                define('SESSION_STATUS', false);
                define('IS_ADMIN', false); //no es admin
              }
            }else{ //el auth no se dividio en 3, no es valido
              define('SESSION_STATUS', false);
              define('IS_ADMIN', false); //no es admin
            }

       }else{ //el auth no existe
         define('SESSION_STATUS', false);
         define('IS_ADMIN', false); //no es admin
       }


      //ya se ha hecho el proceso de revision, ahora se respondera segun el callback
      if($varify_admin == true and IS_ADMIN == false){ //se solicito que fuera admin, pero IS_ADMIN es false
        switch ($callback) {
          case 'API':
            error('No tienes permisos para esto.', 403);
            break;

          case 'PAGUE':
             header('Location: /entrar'); //redirrecionar al login
             exit();
            break;

          default:
            error('El callback no es valido.', 500);
            break;
        }//fin de switch

      }

      if(SESSION_STATUS == false){ //no existe session valida.
        switch ($callback) {
          case 'API':
            error('No tienes permisos para esto.', 403);
            break;

          case 'PAGUE':
             header('Location: /entrar'); //redirrecionar al login
             exit();
            break;

          default:
            error('El callback no es valido.', 500);
            break;
        }//fin de switch

      }


}//fin de checkSession
