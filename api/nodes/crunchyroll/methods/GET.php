<?php

//responder ids para el proxy


//abrir SQL
$mysqli = new mysqli(HOST, USER, PASSWORD, DATABASE);


    //consultar si existe la ID enviada
    $prep_stmt = "SELECT a.id, a.idEpisodio, a.idCrunchy, a.subRequerido, a.subtitleId, a.streamInfo, a.pass  FROM crunchyroll as a, episodios as b WHERE a.pass = 0 AND a.idEpisodio = b.id ORDER BY RAND() LIMIT 50;";
    $stmt = $mysqli->prepare($prep_stmt);

    $stmt->execute();
    $stmt->store_result();

    //asignar valores recibidos
    $stmt->bind_result($id, $idEpisodio, $idCrunchy, $subRequerido, $subtitleId, $streamInfo, $pass);

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
                               "pass" => $pass
                             );
    }

respuesta_ok($responseData, 200);
