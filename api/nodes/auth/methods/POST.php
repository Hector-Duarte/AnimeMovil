<?php
//validar session



//crear session
function createSession($usuario, $password){
/* Esta función solo se usara en la api por lo que no necesita diferentes tipos de callbaks */

  //abrir sql
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
            //no hay resultados, se cerrar toda conexión con la sql ya que terminara el proceso con un error
            $stmt->close(); //cerrar sentencia
            $mysqli->close(); //cerrar sql
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
         $token_access = generateHash($session_user_id.$session_user_name.$session_user_level.$token_expire); //token hash de acceso para la sesión --- hash_primary

         //insertar datos en la tabla de sessiones
         $prep_stmt = "INSERT INTO sessions(user_id, user_level, ip, token, expire) VALUES (?,?,?,?,?);";
         $stmt = $mysqli->prepare($prep_stmt);
         $stmt->bind_param('iissi', $session_user_id, $session_user_level, getUserIp(), $token_access, $token_expire);
         $stmt->execute();

         $session_id = $stmt->insert_id; //ID de la session

         $hash_check = generateHash($session_id.$token_access); //hash_secundary

         /* termino el proceso, cerrar conexion */
         $stmt->close(); //cerrar sentencia
         $mysqli->close(); //cerrar sql

         respuesta_ok( array( "auth" => urlencode("$session_id:$token_access:$hash_check"), "expire" => $token_expire, "expire_in" => 1296000  ) , 201); //retornar la id generada y terminar function

       } //fin de else


} //fin de createSession





//entrada de datos
$input = json_decode(file_get_contents('php://input'));

//asignar valores
$username = $input->user;
$password = $input->password;


if( isset($username) and isset($password) ){ //validar si son aceptables los valores
createSession($username, $password);
}else{
  error('ingresa información valida.', 400);
}
