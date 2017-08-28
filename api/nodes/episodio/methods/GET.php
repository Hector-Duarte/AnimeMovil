<?php

function getEpisodioById(){//obtiene el anime por ID
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

    $prep_stmt = "SELECT id, status, title, slug, numEpi, imgCustom, parentId, message FROM episodios WHERE id = ? LIMIT 1;"; //id es el del anime.
    $stmt = $mysqli->prepare($prep_stmt);

    $stmt->bind_param('i', $anime_id);
    $stmt->execute();
    $stmt->store_result();

    //asignar valores recibidos
    $stmt->bind_result($id, $status, $title, $slug, $numEpi, $imgCustom, $parentId, $message);

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
                                       "numEpi" => $numEpi,
                                       "imgCustom" => $imgCustom,
                                       "parentId" => $parentId,
                                       "message" => $message
                          ) ), "count" => 1 ) , 200);
    }else{
      //no hay respuesta, retornar un error 404
      error('No se ha encontrado el episodio', 404);
    }

}//fin de getAnimeById

function getEpisodio(){
  /* Esta función devolvera episodios en lista.
  solo se aceptaran dos parametros (offset y search) */

  //abrir SQL
  $mysqli = new mysqli(HOST, USER, PASSWORD, DATABASE);
  if($mysqli->connect_errno){ //Fallo la conexión a SQL
      error("No se ha podido conectar con la base de datos.", 500);
  }



      //armar la consulta segun las condiciones
      if( !$_GET['q'] and !$_GET['offset'] ){
        //cuando no se realiza ni una busqueda ni se solicita un offset

        $prep_stmt = "SELECT id, status, title, slug, numEpi, imgCustom, parentId, message FROM episodios ORDER BY id DESC LIMIT 10;"; //id es el del anime.
        $stmt = $mysqli->prepare($prep_stmt);

      }else if( !$_GET['q'] and $_GET['offset'] and is_numeric($_GET['offset']) ){
        //cuando no se busca pero se solicita un offset

        $prep_stmt = "SELECT id, status, title, slug, numEpi, imgCustom, parentId, message FROM episodios ORDER BY id DESC LIMIT 10 OFFSET ?;"; //id es el del anime.
        $stmt = $mysqli->prepare($prep_stmt);
           $offset = $_GET['offset'] * 10; //los offset se multiplican x10
           $stmt->bind_param('i', $offset);

      }else if( $_GET['q'] and !$_GET['offset'] ){
        //cuando se busca pero no se pasa offset
        $prep_stmt = "SELECT id, status, title, slug, numEpi, imgCustom, parentId, message FROM episodios WHERE title like ? ORDER BY id DESC LIMIT 10;"; //id es el del anime.
        $stmt = $mysqli->prepare($prep_stmt);
           $search_like = '%'.$_GET['q'].'%'; //parametro a buscar
           $stmt->bind_param('s', $search_like);

      }else if( $_GET['q'] and $_GET['offset'] and is_numeric($_GET['offset']) ){
        //cuando se busca y se pasa un offset
        $prep_stmt = "SELECT id, status, title, slug, numEpi, imgCustom, parentId, message FROM episodios WHERE title like ? ORDER BY id DESC LIMIT 10 OFFSET ?;"; //id es el del anime.
        $stmt = $mysqli->prepare($prep_stmt);
           $search_like = '%'.$_GET['q'].'%'; //parametro a buscar
           $offset = $_GET['offset'] * 10; //los offset se multiplican x10
           $stmt->bind_param('si', $search_like, $offset);

      }else{
        //no hay combinación valida
        error('No existe combinación valida para realizar esta consulta.', 400);
      }


      $stmt->execute();
      $stmt->store_result();

      //asignar valores recibidos
      $stmt->bind_result($id, $status, $title, $slug, $numEpi, $imgCustom, $parentId, $message);

      //array contenedor
      $items = array();

      //obtener resultados
      while( $stmt->fetch() ){
        //obtener todos los resultados
                       $items[] =   array(
                         "id" => $id,
                         "status" => $status,
                         "title" => $title,
                         "slug" => $slug,
                         "numEpi" => $numEpi,
                         "imgCustom" => $imgCustom,
                         "parentId" => $parentId,
                         "message" => $message
                                       );
      }


            $stmt->close(); //cerrar sentencia
            $mysqli->close(); //cerrar sql

      respuesta_ok( array( "items" => $items, "count" => count($items) ) , 200);



}//fin de getAnime




/* RESPUESTAS SEGUN LA REQUEST  */

if( is_numeric($_GET['value']) ){
  //el valor es numerico, por lo que se buscara el episodio.
  getEpisodioById();
}else{
  //no es numerico, se evaluara como tipo buscador (offset y search)
  getEpisodio();
}
