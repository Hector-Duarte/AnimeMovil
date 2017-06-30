<?php


//abrir SQL
$mysqli = new mysqli(HOST, USER, PASSWORD, DATABASE);


    $prep_stmt = "INSERT INTO favoritos (id, nodeId) VALUES (?, ?);";
    $stmt = $mysqli->prepare($prep_stmt);

    $stmt->bind_param('ii', $_COOKIE["session_user_id"], $_GET["value"]);



if( $stmt->execute() ){

   respuesta_ok( array( "available" => true ), 201);
}else{

   respuesta_ok( array( "available" => false ), 202);
}



//cerar SQL
$stmt->close();
$mysqli->close();