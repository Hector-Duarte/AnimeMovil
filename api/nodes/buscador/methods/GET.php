<?php

//abrir SQL
$mysqli = new mysqli(HOST, USER, PASSWORD, DATABASE);


    $prep_stmt = "SELECT id, slug FROM animes WHERE status = 1 AND title LIKE ? LIMIT ? OFFSET ?;";
    $stmt = $mysqli->prepare($prep_stmt);




//consulta para cadena 
function sql_like(){
    if( $_GET["q"] AND strlen( $_GET["q"] ) <= 70  ){
    $text = str_replace(' ', '-', $_GET["q"]);
    $text = preg_replace("/[^A-Za-z0-9\-]/", "", $text) ;
    $text = " a.title LIKE '%". $text ."%' ";
    $text = str_replace('-', '%', $text);
    return $text;
    }else{
          return 1;
          }
}


function sql_offset(){
    if( is_numeric($_GET["offset"]) ){
    $text = $_GET["offset"];
    $text = $text*15;
    return $text;
    }else{
          return 0;
          }
}

function sql_letra(){
    if($_GET["letra"] AND $_GET["letra"] != "ALL" AND strlen($_GET["letra"]) == 1 ){
    $text = ereg_replace("[^A-Z]", "", $_GET["letra"]);
    $text = " a.title LIKE '". $text ."%' ";
    return $text;
    }else{
          return 1;
          }
}

function sql_genero(){
    if($_GET["genero"] AND is_numeric($_GET["genero"]) ){
    $text = $_GET["genero"];
    $text = " b.idGenero = ". $text ." AND a.id = b.idAnime ";
    return $text;
    }else{
          return 1;
          }
}

function sql_estado(){
    if( is_numeric($_GET["estado"]) AND $_GET["estado"] < 2 ){
    $text = $_GET["estado"];
    $text = " a.simulcasts = ". $text ." ";
    return $text;
    }else{
          return 1;
          }
}



function sql_limit(){
  if( is_numeric($_GET["limit"]) AND $_GET["limit"] < 30 ){
   $text = $_GET["limit"];
   return $text;
    }else{
      return 10;
  }
   }

    $prep_stmt = "SELECT DISTINCT a.id, a.title, a.slug FROM animes as a, generos as b WHERE ". sql_like() ." AND ". sql_letra() ." AND ". sql_genero() ." AND ". sql_estado() ." ORDER BY a.id desc LIMIT ". sql_limit() ." OFFSET ". sql_offset() ." ;";




    $stmt = $mysqli->prepare($prep_stmt);

    $stmt->execute();
    $stmt->store_result();

    //asignar valores recibidos
    $stmt->bind_result($id, $title, $slug);
    



               
              $resultados = array();


                 while ( $stmt->fetch() ) {
                     $resultados[] = array( "id" => $id, "title" => $title, "slug" => $slug );
                 }



//imprimir contenido
respuesta_ok( array( "items" => $resultados, "count" => count($resultados) ), 200);


//cerar SQL
$stmt->close();
$mysqli->close();