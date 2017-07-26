<?php

//abrir SQL
$mysqli = new mysqli(HOST, USER, PASSWORD, DATABASE);


    $prep_stmt = "DELETE FROM favoritos WHERE id = ? AND nodeId = ?  LIMIT 1;";
    $stmt = $mysqli->prepare($prep_stmt);

    $user_id = USER_ID;//la ID del usuario.
    $stmt->bind_param('ii', $user_id, $_GET["value"]);

    $stmt->execute(); //ejecutar borrado

    $del_exitoso = $stmt->affected_rows; //obtiene el numero de filas borradas (tiene que ser 1 que es true)

              $stmt->close(); //cerrar sentencia
              $mysqli->close(); //cerrar sql

if( $del_exitoso ){ //si se afecto una fila es que se borro (1 es true)

   respuesta_ok( array( "available" => false, "message" => "Borrado con exito." ), 200);
}else{

   respuesta_ok( array( "available" => true, "message" => "No se podido borrar el anime." ), 202);
}



//cerar SQL
$stmt->close();
$mysqli->close();
