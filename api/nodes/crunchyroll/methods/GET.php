<?php

//abrir SQL
$mysqli = new mysqli(HOST, USER, PASSWORD, DATABASE);

//function para obtener todas las ids disponibles
function getIds(){
    /* Esta funcion obtiene todas las ids con pass=0, se usara para que el proxy
      espere el subtitulo en espanil y posteriormente subira los datos y acutalizara el pass=1  */

    //consultar si existe la ID enviada
    $prep_stmt = "SELECT a.id, a.idEpisodio, a.idCrunchy, a.subRequerido, a.subtitleId, a.streamInfo, a.pass, c.title, c.id FROM crunchyroll as a, episodios as b, animes as c WHERE a.pass = 0 AND a.idEpisodio = b.id AND b.parentId = c.id AND c.status = 1 ORDER BY RAND() LIMIT 50;";
    $stmt = $mysqli->prepare($prep_stmt);

    $stmt->execute();
    $stmt->store_result();

    //asignar valores recibidos
    $stmt->bind_result($id, $idEpisodio, $idCrunchy, $subRequerido, $subtitleId, $streamInfo, $pass, $anime_title, $anime_id);

    //array contenedor
    $responseData = array();

    //ciclo para imprimir valores
    while($stmt->fetch() ){

      $responseData[] = array( "id" => $id,
                               "idEpisodio" => $idEpisodio,
                               "idCrunchy" => $idCrunchy,
                               "subRequerido" => $subRequerido,
                               "subtitleId" => $subRequerido,
                               "streamInfo" => $streamInfo,
                               "pass" => $pass,
                               "anime_title" => $anime_title,
                               "anime_id" => $anime_id
                             );
    }


respuesta_ok( array( "items" => $responseData, "count" => count($responseData) ), 200);
}
// fin de la function getIds



//condiciones para responder segun la petición
          if($value == "pending"){
            getIds();
          }
