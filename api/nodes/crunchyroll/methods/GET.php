<?php

//abrir SQL
$mysqli = new mysqli(HOST, USER, PASSWORD, DATABASE);
if($mysqli->connect_errno){ //Fallo la conexión a SQL
    error("No se ha podido conectar con la base de datos.", 500);
}


//function para obtener todas las ids disponibles
if($_GET['value'] == 'pending'){
    /* Esta funcion obtiene todas las ids con pass=0, se usara para que el proxy
      espere el subtitulo en espanil y posteriormente subira los datos y acutalizara el pass=1  */

    //consultar todas las ids disponibles
    $prep_stmt = "SELECT a.id, a.idEpisodio, a.idCrunchy, a.subRequerido, a.subtitleId, a.streamInfo, a.pass, c.title, c.id FROM crunchyroll as a, episodios as b, animes as c, stream as d WHERE a.pass = 0 AND a.idEpisodio = b.id AND b.parentId = c.id AND c.status = 1 AND d.id = b.id ORDER BY RAND() LIMIT 50;";
    $stmt = $mysqli->prepare($prep_stmt);

    $stmt->execute();
    $stmt->store_result();

    //asignar valores recibidos
    $stmt->bind_result($id, $idEpisodio, $idCrunchy, $subRequerido, $subtitleId, $streamInfo, $pass, $anime_title, $anime_id);

    //array contenedor
    $responseData = array();

    //ciclo para imprimir valores
    while( $stmt->fetch() ){

      $responseData[] = array( "id" => $id,
                               "idEpisodio" => $idEpisodio,
                               "idCrunchy" => $idCrunchy,
                               "subRequerido" => $subRequerido,
                               "subtitleId" => $subtitleId,
                               "streamInfo" => $streamInfo,
                               "pass" => $pass,
                               "parent" => array( "id" => $anime_id, "title" => $anime_title ),
                               "episodio_stream_info" => array( "path" => $stream_path, "file" => $stream_file )
                             );
       }//fin while


    respuesta_ok( array( "items" => $responseData, "count" => count($responseData) ), 200);
  }//fin de if pending




  //function para obtener todas las ids disponibles
  if($_GET['value'] == 'pendingEncoding'){
      /* Esta funcion obtiene todas las ids con pass=0, se usara para que el proxy
        espere el subtitulo en espanil y posteriormente subira los datos y acutalizara el pass=1  */

      //consultar todas las ids disponibles
      $prep_stmt = "SELECT a.id, a.idEpisodio, a.idCrunchy, a.subRequerido, a.subtitleId, a.streamInfo, a.pass, c.title, c.id, d.path, d.file FROM crunchyroll as a, episodios as b, animes as c, stream as d WHERE a.pass = 1 AND a.idEpisodio = b.id AND b.parentId = c.id AND c.status = 1 AND d.id = b.id ORDER BY RAND() LIMIT 50;";
      $stmt = $mysqli->prepare($prep_stmt);

      $stmt->execute();
      $stmt->store_result();

      //asignar valores recibidos
      $stmt->bind_result($id, $idEpisodio, $idCrunchy, $subRequerido, $subtitleId, $streamInfo, $pass, $anime_title, $anime_id, $stream_path, $stream_file);

      //array contenedor
      $responseData = array();

      //ciclo para imprimir valores
      while( $stmt->fetch() ){

        $responseData[] = array( "id" => $id,
                                 "idEpisodio" => $idEpisodio,
                                 "idCrunchy" => $idCrunchy,
                                 "subRequerido" => $subRequerido,
                                 "subtitleId" => $subtitleId,
                                 "streamInfo" => $streamInfo,
                                 "pass" => $pass,
                                 "parent" => array( "id" => $anime_id, "title" => $anime_title ),
                                 "episodio_stream_info" => array( "path" => $stream_path, "file" => $stream_file )
                               );
         }//fin while


      respuesta_ok( array( "items" => $responseData, "count" => count($responseData) ), 200);
    }//fin de if pending





  if( is_numeric($_GET['value']) ){
    //se esta recibiendo un numero, se consultara

        //consultar todas las ids disponibles
        $prep_stmt = "SELECT a.id, a.idEpisodio, a.idCrunchy, a.subRequerido, a.subtitleId, a.streamInfo, a.pass, c.title, c.id FROM crunchyroll as a, episodios as b, animes as c WHERE a.id = ? AND a.idEpisodio = b.id AND b.parentId = c.id AND c.status = 1 LIMIT 1;";
        $stmt = $mysqli->prepare($prep_stmt);
        $stmt->bind_param('i', $_GET['value']);
        $stmt->execute();
        $stmt->store_result();

        //asignar valores recibidos
        $stmt->bind_result($id, $idEpisodio, $idCrunchy, $subRequerido, $subtitleId, $streamInfo, $pass, $anime_title, $anime_id);

        //array contenedor
        $responseData = array();

        //ciclo para imprimir valores
        while( $stmt->fetch() ){

          $responseData[] = array( "id" => $id,
                                   "idEpisodio" => $idEpisodio,
                                   "idCrunchy" => $idCrunchy,
                                   "subRequerido" => $subRequerido,
                                   "subtitleId" => $subtitleId,
                                   "streamInfo" => $streamInfo,
                                   "pass" => $pass,
                                   "anime_title" => $anime_title,
                                   "anime_id" => $anime_id
                                 );
           }//fin while


        respuesta_ok( array( "items" => $responseData, "count" => count($responseData) ), 200);
  }
