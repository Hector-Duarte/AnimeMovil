<?php

//validar session

//asignar valores
if($_COOKIE['session_id']){
  $session_id = $_COOKIE['session_id']; //asignar de cookie
}else if($_GET['session_id']){
  $session_id = $_GET['session_id']; //asignar de parametro GET ?session_id=IDSESSION
}else{
    error('session_id necesaria.', 400); //no se han encontrado parametros validos.
}

if( isset($session_id) ){ //validar si son aceptables los valores
checkSession($session_id, 'API');

      if(SESSION_STATUS){ //si la session es valida.

        setcookie("session_id", $_SESSION['session_ip'], $_SESSION['session_expire'], "/"); //asignar cookie

        respuesta_ok( array(
        "id" => session_id(),
        "ip" => $_SESSION['session_ip'],
        "expire" => date('m-d-Y H:i:s', $_SESSION['session_expire']),
        "user_info" =>      array(
                                  "id" => $_SESSION['user_id'],
                                  "username" => $_SESSION['user_name'],
                                  "level" => $_SESSION['user_level']
                                 )
                           ), 200);

      }

}else{
  error('session_id no valida.', 400);
}
