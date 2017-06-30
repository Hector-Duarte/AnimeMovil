<?php

//recibir datos del proxy 


//abrir SQL
$mysqli = new mysqli(HOST, USER, PASSWORD, DATABASE);



//entrada de datos
$input = json_decode(file_get_contents('php://input'));

//recojer valores
$idCrunchy = $input->id; //id del episodio en crunchyroll
$slugRoot = $input->slugRoot; //slug del anime de crunchyroll


    //consultar si existe la ID enviada
    $prep_stmt = "SELECT idCrunchy FROM crunchyroll WHERE idCrunchy = ? LIMIT 1;";
    $stmt = $mysqli->prepare($prep_stmt);

    $stmt->bind_param('i', $idCrunchy);
    $stmt->execute();
    $stmt->store_result();

    //asignar valores recibidos
    $stmt->bind_result($idCrunchy_sql);
    $stmt->fetch();


    //comprobar si la id existe
    if( $idCrunchy_sql == $idCrunchy){
    	//la id existe, finalizar proceso para evitar el reencoding del video
    	respuesta_ok( array( "message" => 'El ID ya existe, no se ha procesado.' ), 202);
    }



    //consultar los datos para agregar y comprobar que existe anime contenedor
    $prep_stmt = "SELECT idAnime, slugAnimeCrunchy, calidadPrimaria, title, path, number_start FROM crunchyroll_anime WHERE slugAnimeCrunchy = ? LIMIT 1;";
    $stmt = $mysqli->prepare($prep_stmt);

    $stmt->bind_param('s', $slugRoot);
    $stmt->execute();
    $stmt->store_result();

    //asignar valores recibidos
    $stmt->bind_result($idAnime, $slugAnimeCrunchy, $calidadPrimaria, $title, $path, $number_start);
    $stmt->fetch();

    //pasar a utf-8
    $title = utf8_encode($title);


    if( $slugRoot != $slugAnimeCrunchy ){
    	//no existe el slugRoot, detener proceso (no existe anime con ese registro)
    	respuesta_ok( array( "message" => 'El slugRoot no tiene anime contenedor, no se ha procesado.' ), 202);
    }



//nuevo episodio, sumar 1 (se asgina)
$number_start+=1;

//asignar el nuevo titulo para el episodio
$title = str_replace("EPISODE_NUMBER", $number_start, $title);

//preparar slug
function slug_generate($text)
{
    $text= strtolower($text);
    $text= html_entity_decode($text);
    $text= str_replace(array('ä','ü','ö','ß','ñ','á','é','í','ó','ú'),array('a','u','o','b','n','a','e','i','o','u'),$text);
    $text= preg_replace('#[^\w\säüöß]#',null,$text);
    $text= preg_replace('#[\s]{2,}#',' ',$text);
    $text= str_replace(array(' '),array('-'),$text);
    return $text;

}
$slug = slug_generate($title);


    //crear episodio y agregar a la DB
    $prep_stmt = "INSERT INTO episodios (status, title, slug, numEpi, imgCustom, parentId, simulcasts, message) VALUES (0, ?, ?, ?, 0, ?, 1, 'Procesando versión HD...');";
    $stmt = $mysqli->prepare($prep_stmt);
    $stmt->bind_param('ssii', $title, $slug, $number_start, $idAnime);
    $stmt->execute();
    $createId=$stmt->insert_id;

    //crear registro en la tabla stream
    $prep_stmt = "INSERT INTO stream  (id, path, file) VALUES (?, ?, ?);";
    $stmt = $mysqli->prepare($prep_stmt);
    $stmt->bind_param('iss', $createId, $path, $number_start);
    $stmt->execute();


    //agregar a la tabla crunchyroll para su procesamiento por el proxy (stream raw y subtitulos)
    $prep_stmt = "INSERT INTO crunchyroll  (idEpisodio, idCrunchy, subRequerido, subtitleId, streamInfo, pass) VALUES (?, ?, 1, 0, '0', 0);";
    $stmt = $mysqli->prepare($prep_stmt);
    $stmt->bind_param('ii', $createId, $idCrunchy);
    $stmt->execute();

    //sumar 1 al anime en la table crunchyroll_anime
    $prep_stmt = "UPDATE crunchyroll_anime SET number_start = number_start+1 WHERE idAnime = ? LIMIT 1;";
    $stmt = $mysqli->prepare($prep_stmt);
    $stmt->bind_param('i', $idAnime);
    $stmt->execute();


//enviar datos con los que se creo el episodio
respuesta_ok( array( "newId" => $createId, "idAnime" => $idAnime, "crunchySlug" => $slugAnimeCrunchy, "calidadPrimaria" => $calidadPrimaria, "title" => $title, "path" => $path, "episode_number" => $number_start  ), 201);


















//cerar SQL
$stmt->close();
$mysqli->close();