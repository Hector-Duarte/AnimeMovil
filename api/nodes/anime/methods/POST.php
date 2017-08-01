<?php

//validar session
checkSession('API', true); //'API' es el tipo de callback y el true es que tiene que ser admin.

/*
OPCIONALES...
$_POST['message']
$_POST['nextEpi']
$_POST['collection'] se debe de declarar 0 para no tener efecto
*/

//verificar que se mandaron todos los datos
if( !isset($_POST['status'], $_POST['nextEpi'], $_POST['message'], $_POST['title'], $_POST['simulcasts'], $_POST['sinopsis'], $_POST['emision'], $_POST['collection'], $_FILES['IMG_portada']['tmp_name'], $_FILES['IMG_wallpaper']['tmp_name'] ) ){
  error('Por favor ingrese todos los datos necesarios y vuelva a intentar.', 400);
}




//abrir SQL
$mysqli = new mysqli(HOST, USER, PASSWORD, DATABASE);
if($mysqli->connect_errno){ //Fallo la conexión a SQL
    error("No se ha podido conectar con la base de datos.", 500);
}

     //preparar insert
     $prep_stmt = "INSERT INTO animes(status, title, slug, simulcasts, sinopsis, emision, nextEpi, collection, message) VALUES (?,?,?,?,?,?,?,?,?);"; //id es el del anime.
     $stmt = $mysqli->prepare($prep_stmt);

     $anime_slug = slug_generate( $_POST['title'] );

     $stmt->bind_param('ississsis', $_POST['status'], $_POST['title'], $anime_slug, $_POST['simulcasts'], $_POST['sinopsis'], $_POST['emision'], $_POST['nextEpi'], $_POST['collection'], $_POST['message']);
     $stmt->execute(); //ejecutar consulta.

     $anime_id_new_create = $stmt->insert_id; //ID del anime nuevo.

$stmt->close(); //cerrar sentencia
$mysqli->close(); //cerrar sql

if( !$anime_id_new_create ){//si el ID no es valido
      error('Ocurrio un error al agregar el anime.', 400);
    }


//cargar imagenes a azure
/*
resoluciones:
                IMG_portada => 240 x 360
                IMG_portada => 170 x 250
                IMG_portada => 90 x 90
                IMG_wallpaper => 1280 x 720
                IMG_wallpaper => 848 x 480
                IMG_wallpaper => 640 x 360
*/

//cargar imagenes...

     //portada
     $datos  = array(
        'original' => $_FILES['IMG_portada']['tmp_name'],
        'contenedor' => 'animes',
        'blob' => "$anime_id_new_create/banner_full.jpg",
        'resolucion' => array(240,360),
        'calidad' => 100
     );
     miniaturas($datos);

     //portada 170 x 250
     $datos  = array(
        'original' => $_FILES['IMG_portada']['tmp_name'],
        'contenedor' => 'animes',
        'blob' => "$anime_id_new_create/banner_small.jpg",
        'resolucion' => array(170,250),
        'calidad' => 100
     );
     miniaturas($datos);


     //miniatura 90x90
     $datos  = array(
        'original' => $_FILES['IMG_portada']['tmp_name'],
        'contenedor' => 'animes',
        'blob' => "$anime_id_new_create/miniature.jpg",
        'resolucion' => array(90,90),
        'calidad' => 100
     );
     miniaturas($datos);

     //wallpaper 1280x720
     $datos  = array(
        'original' => $_FILES['IMG_wallpaper']['tmp_name'],
        'contenedor' => 'animes',
        'blob' => "$anime_id_new_create/wallpaper_full.jpg",
        'resolucion' => array(1280,720),
        'calidad' => 100
     );
     miniaturas($datos);

     //wallpaper 848 x 480
     $datos  = array(
        'original' => $_FILES['IMG_wallpaper']['tmp_name'],
        'contenedor' => 'animes',
        'blob' => "$anime_id_new_create/wallpaper_medium.jpg",
        'resolucion' => array(848,480),
        'calidad' => 100
     );
     miniaturas($datos);

     //wallpaper 640 x 360
     $datos  = array(
        'original' => $_FILES['IMG_wallpaper']['tmp_name'],
        'contenedor' => 'animes',
        'blob' => "$anime_id_new_create/wallpaper_small.jpg",
        'resolucion' => array(640,360),
        'calidad' => 100
     );
     miniaturas($datos);

/*
$datos  = array(
'original' => $_FILES['img']['tmp_name'],
'contenedor' => 'animes',
'blob' => 'blob.jpg',
'resolucion' => array(90,90),
'calidad' => 100
);
miniaturas($datos);
*/
//FIN cargar imagenes.

//responder con el ID del anime nuevo para hacer redicción.
respuesta_ok( array( "id" => $anime_id_new_create ) , 201);
