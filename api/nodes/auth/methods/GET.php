<?php

//validar session

//entrada de datos
$input = json_decode(file_get_contents('php://input'));

//asignar valores
$session_id = $_COOKIE['session_id'];


if( isset($session_id) ){ //validar si son aceptables los valores
checkSession($session_id, 'API');

      if(SESSION_STATUS){ //si la session es valida.
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
  error('cookie no valida.', 400);
}
