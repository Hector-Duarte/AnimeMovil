<?php


//abrir SQL
$mysqli = new mysqli(HOST, USER, PASSWORD, DATABASE);


    $prep_stmt = "INSERT INTO favoritos (id, nodeId) VALUES (?, ?);";
    $stmt = $mysqli->prepare($prep_stmt);

    $user_id = USER_ID;//la ID del usuario.

    $stmt->bind_param('ii', $user_id, $_GET["value"]);
    $stmt->execute(); //ejecutar


    $insert_exitoso = $stmt->affected_rows; //obtiene el numero de filas borradas (tiene que ser 1 que es true)

    $stmt->close(); //cerrar sentencia
    $mysqli->close(); //cerrar sql


if( $insert_exitoso ){

   respuesta_ok( array( "available" => true, "message" => "Agregado con exito." ), 201);
}else{

   respuesta_ok( array( "available" => false, "meesage" => "No se ha podido agregar." ), 202);
}
