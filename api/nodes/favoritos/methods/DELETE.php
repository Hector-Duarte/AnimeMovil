<?php

//abrir SQL
$mysqli = new mysqli(HOST, USER, PASSWORD, DATABASE);


    $prep_stmt = "DELETE FROM favoritos WHERE id = ? AND nodeId = ?  LIMIT 5;";
    $stmt = $mysqli->prepare($prep_stmt);

    $stmt->bind_param('ii', $_COOKIE["session_user_id"], $_GET["value"]);


if( $stmt->execute() ){

   respuesta_ok( array( "available" => false ), 200);
}else{

   respuesta_ok( array( "available" => true ), 200);
}



//cerar SQL
$stmt->close();
$mysqli->close();