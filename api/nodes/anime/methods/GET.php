<?php

function getAnimeById(){//obtiene el anime por ID
//abrir SQL
$mysqli = new mysqli(HOST, USER, PASSWORD, DATABASE);
if($mysqli->connect_errno){ //Fallo la conexión a SQL
    error("No se ha podido conectar con la base de datos.", 500);
}


//evaluar id para ver si es valida para consultar.
$anime_id = $_GET["value"]; //id del anime.
if( !is_numeric($anime_id) ){
  error('Ingrese un id valido.', 400);
}

    $prep_stmt = "SELECT id, status, title, slug, simulcasts, sinopsis, emision, nextEpi, collection, message FROM animes WHERE id = ? LIMIT 1;"; //id es el del anime.
    $stmt = $mysqli->prepare($prep_stmt);

    $stmt->bind_param('i', $anime_id);
    $stmt->execute();
    $stmt->store_result();

    //asignar valores recibidos
    $stmt->bind_result($id, $status, $title, $slug, $simulcasts, $sinopsis, $emision, $nextEpi, $collection, $message);

    //obtener resultados
    $respuesta_exitosa = $stmt->fetch();

    $stmt->close(); //cerrar sentencia
    $mysqli->close(); //cerrar sql

    if( $respuesta_exitosa ){
      //hay anime encontrador, imprimir
      respuesta_ok( array( "items" => array( 0 => array(
                                       "id" => $id,
                                       "status" => $status,
                                       "title" => $title,
                                       "slug" => $slug,
                                       "simulcasts" => $simulcasts,
                                       "sinopsis" => $sinopsis,
                                       "emision" => $emision,
                                       "nextEpi" => $nextEpi,
                                       "collection" => $collection,
                                       "message" => $message
                          ) ) ) , 200);
    }else{
      //no hay respuesta, retornar un error 404
      error('No se ha encontrado el anime', 404);
    }

}//fin de getAnimeById






/* RESPUESTAS SEGUN LA REQUEST  */

if( is_numeric($_GET['value']) ){
  //el valor es numerico, por lo que se buscara el anime.
  getAnimeById();
}
