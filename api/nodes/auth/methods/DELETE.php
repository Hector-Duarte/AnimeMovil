<?php

//validar session
checkSession('API', false);  //'API' es el tipo de callback y el false es que no es necesario que sea admin


if(SESSION_STATUS){ //si la session es valida.
  //abrir sql
  $mysqli = new mysqli(HOST, USER, PASSWORD, DATABASE);

      $prep_stmt = "DELETE FROM sessions WHERE id=? LIMIT 1;";
      $stmt = $mysqli->prepare($prep_stmt);
      $session_id = SESSION_ID; //session de id
      $stmt->bind_param('i', $session_id); //pasar la id de la session
      $stmt->execute(); //ejecutar borrado

      $del_exitoso = $stmt->affected_rows; //obtiene el numero de filas borradas (tiene que ser 1 que es true)

          $stmt->close(); //cerrar sentencia
          $mysqli->close(); //cerrar sql


          //responder al usuario
          if($del_exitoso == 1){ //el borrado fue correcto
            respuesta_ok( array( "message" => "se ha cerrado la sesión exitosamente." ) , 200);
          }else{//no se ha borrado la session.
            error('Ha ocurrido un error y no se ha cerrado la sesión.', 400);
          }

}
