<?php

//validar session

//entrada de datos
$input = json_decode(file_get_contents('php://input'));

//asignar valores
$session_id = $input->session_id;


if( isset($session_id) ){ //validar si son aceptables los valores
checkSession($session_id, 'API');

      if(SESSION_STATUS){ //si la session es valida.
        session_destroy();//destruir session

      }

}else{
  error('session_id no valida.', 400);
}
