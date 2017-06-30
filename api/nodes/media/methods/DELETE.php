<?php


//abrir SQL
$mysqli = new mysqli(HOST, USER, PASSWORD, DATABASE);


    $prep_stmt = "UPDATE episodios SET imgCustom = 0 WHERE id = ? LIMIT 1;";
    $stmt = $mysqli->prepare($prep_stmt);

    $stmt->bind_param('i', $_GET['value']);

if( $stmt->execute() ){
	//Se borro
	respuesta_ok( array("delete" => true ) ,200);
}else{
	//no se borro
    respuesta_ok( array( "delete" => false ) ,200);
}



//cerar SQL
$stmt->close();
$mysqli->close();