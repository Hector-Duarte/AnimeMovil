<?php

//abrir SQL
$mysqli = new mysqli(HOST, USER, PASSWORD, DATABASE);
if($mysqli->connect_errno){ //Fallo la conexión a SQL
    error("No se ha podido conectar con la base de datos.", 500);
}

    //ELIMINAR si existe la ID enviada
    $prep_stmt = "DELETE FROM crunchyroll WHERE id = ? LIMIT 1;";
    $stmt = $mysqli->prepare($prep_stmt);

    $stmt->bind_param('i', $_GET["value"]);
    $stmt->execute();

        	respuesta_ok( array( "message" => 'El ID se ha borrado.' ), 204);
