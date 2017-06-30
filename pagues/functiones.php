<?php
//FUNCIONES PARA ADMIN PANEL	- requerido require php de variables


//generar firma para descargar
function startSession(){


if (isset($_POST['usuario'], $_POST['password'])){
//iseet

//entrada de datos
    $usuario = filter_input(INPUT_POST, 'usuario', FILTER_SANITIZE_STRING);
    $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);




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
                     header("Location: /entrar?error=1");
                     exit(); 

            }else{

       //destruir passwords
       unset($password);
       unset($db_password);
       unset($db_salt);


       //iniciar sesion




              //valores de las cookies
              $session_expire = time()+1296000; //expira en 15 dias
              $session_id = uniqid(); //id de session unica


              $_arraysign = array();
               $_arraysign[] = $session_user_id; //id del usuario
               $_arraysign[] = $session_user_name; //nombre de usuario
               $_arraysign[] = $session_id; //session id
               $_arraysign[] = $session_user_level; //nivel de usuario (0=admin && 1=usuario estandar)
               $_arraysign[] = $session_expire; //expira en 15 dias
               $_arraysign[] = SIGNATURE_HASH_USER; //key hash

               $_str2sign = implode("\n", $_arraysign);
 
              $session_hash = base64_encode( hash_hmac('sha256', urldecode(utf8_encode($_str2sign)), base64_decode($key), true) ); //hast token para verificar sesion


              //asignar cookies 
              setcookie("session_user_id", $session_user_id, $session_expire, "/");
              setcookie("session_user_name", $session_user_name, $session_expire, "/");
              setcookie("session_id", $session_id, $session_expire, "/");
              setcookie("session_user_level", $session_user_level, $session_expire, "/");
              setcookie("session_expire", $session_expire, $session_expire, "/");
              setcookie("session_hash", $session_hash, $session_expire, "/");



       //enviar al panel
       header("Location: /panel");
       exit(); 
       }



//FIN isset
}

}


//Verificar session valida
function validateSession($errorRespuesta){

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
                          //respuesta de error
                               if($errorRespuesta == 1){
                                 header("Location: /entrar");exit();
                               }else if($errorRespuesta == 2){
                                 echo json_encode( array( "error" => "Session no valida" ) );exit();
                               }
          return false;

           }


}//fin comprobar si existe hash
else{
//respuesta de error
                               if($errorRespuesta == 1){
                                 header("Location: /entrar");exit();
                               }else if($errorRespuesta == 2){
                                 echo json_encode( array( "error" => "Session no valida" ) );exit();
                               }

}

}


//verificar si es admin
function adminValidate(){

if($_COOKIE["session_user_level"] != 0){
echo '<div class="errorLogin"><span><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> No tienes suficientes permisos.</span></div>';
exit();
}

}


?>

