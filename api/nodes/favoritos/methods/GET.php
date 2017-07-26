<?php

//abrir SQL
$mysqli = new mysqli(HOST, USER, PASSWORD, DATABASE);
if($mysqli->connect_errno){ //Fallo la conexión a SQL
    error("No se ha podido conectar con la base de datos.", 500);
}

    $prep_stmt = "SELECT id, nodeId FROM favoritos WHERE id = ? AND nodeId = ?  LIMIT 1;"; //id es el usuarioId y nodeId es la id del anime.
    $stmt = $mysqli->prepare($prep_stmt);

    $user_id = USER_ID;//la ID del usuario.

    $stmt->bind_param('ii', $user_id, $_GET["value"]);
    $stmt->execute();
    $stmt->store_result();

    //asignar valores recibidos
    $stmt->bind_result($id, $nodeId);

    //obtener resultados
    $respuesta_exitosa = $stmt->fetch();

    $stmt->close(); //cerrar sentencia
    $mysqli->close(); //cerrar sql


if( $respuesta_exitosa ){
   //se encontraron valores por lo que es valido
   respuesta_ok( array( "available" => true ), 200);
}else{
   //no hay valores devueltos, no existen.
   respuesta_ok( array( "available" => false ), 200);
}
