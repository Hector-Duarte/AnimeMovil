<?php

//abrir SQL
$mysqli = new mysqli(HOST, USER, PASSWORD, DATABASE);


    $prep_stmt = "SELECT id, nodeId FROM favoritos WHERE id = ? AND nodeId = ?  LIMIT 1;";
    $stmt = $mysqli->prepare($prep_stmt);

    $stmt->bind_param('ii', $_COOKIE["session_user_id"], $_GET["value"]);
    $stmt->execute();
    $stmt->store_result();

    //asignar valores recibidos
    $stmt->bind_result($id, $nodeId);
    $stmt->fetch();


if( $_COOKIE["session_user_id"] == $id AND $_GET["value"] == $nodeId ){

   respuesta_ok( array( "available" => true ), 200);
}else{

   respuesta_ok( array( "available" => false ), 200);
}



//cerar SQL
$stmt->close();
$mysqli->close();