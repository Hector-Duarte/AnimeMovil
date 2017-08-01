<?php

//validar session
checkSession('API', true); //'API' es el tipo de callback y el true es que tiene que ser admin.

function createAnime(){//funcion para crear nuevos animes.
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

}//fin de createAnime.



function updateAnime(){
//se usara para actualizar la información del anime.

//asignar la id del anime
$anime_id = $_GET['value'];
if( !is_numeric($anime_id) ){
  //la id es invalida.
  error('Ingrese un ID valido.', 400);
}


//verificar que se mandaron todos los datos
if( !isset($_POST['status'], $_POST['nextEpi'], $_POST['message'], $_POST['title'], $_POST['simulcasts'], $_POST['sinopsis'], $_POST['emision'], $_POST['collection'] ) ){
  error('Por favor ingrese todos los datos necesarios y vuelva a intentar.', 400);
}

/* >>>> INICIO DE IMAGENES <<<< */

//evaluar la imagen de portada, si se envio una se actualizara.
if( isset($_FILES['IMG_portada']['tmp_name']) ){

$files_purge = array();

  //cargar imagenes...
       //portada
       $datos  = array(
          'original' => $_FILES['IMG_portada']['tmp_name'],
          'contenedor' => 'animes',
          'blob' => "$anime_id/banner_full.jpg",
          'resolucion' => array(240,360),
          'calidad' => 100
       );
       miniaturas($datos);
       $files_purge[] = HTTP_PROTOCOL.'://'.STATIC_DOMAIN."/animes/$anime_id/banner_full.jpg"; //archivo a purgar

       //portada 170 x 250
       $datos  = array(
          'original' => $_FILES['IMG_portada']['tmp_name'],
          'contenedor' => 'animes',
          'blob' => "$anime_id/banner_small.jpg",
          'resolucion' => array(170,250),
          'calidad' => 100
       );
       miniaturas($datos);
       $files_purge[] = HTTP_PROTOCOL.'://'.STATIC_DOMAIN."/animes/$anime_id/banner_small.jpg";


       //miniatura 90x90
       $datos  = array(
          'original' => $_FILES['IMG_portada']['tmp_name'],
          'contenedor' => 'animes',
          'blob' => "$anime_id/miniature.jpg",
          'resolucion' => array(90,90),
          'calidad' => 100
       );
       miniaturas($datos);
       $files_purge[] = HTTP_PROTOCOL.'://'.STATIC_DOMAIN."/animes/$anime_id/miniature.jpg";

       //purgar
       purge($files_purge);
}//fin de if si se envio la portada.




//evaluar la imagen de wallpaper, si se envio una se actualizara.
if( isset($_FILES['IMG_wallpaper']['tmp_name']) ){

$files_purge = array();

  //cargar imagenes...
       //portada

            //wallpaper 1280x720
            $datos  = array(
               'original' => $_FILES['IMG_wallpaper']['tmp_name'],
               'contenedor' => 'animes',
               'blob' => "$anime_id/wallpaper_full.jpg",
               'resolucion' => array(1280,720),
               'calidad' => 100
            );
            miniaturas($datos);
            $files_purge[] = HTTP_PROTOCOL.'://'.STATIC_DOMAIN."/animes/$anime_id/wallpaper_full.jpg";

            //wallpaper 848 x 480
            $datos  = array(
               'original' => $_FILES['IMG_wallpaper']['tmp_name'],
               'contenedor' => 'animes',
               'blob' => "$anime_id/wallpaper_medium.jpg",
               'resolucion' => array(848,480),
               'calidad' => 100
            );
            miniaturas($datos);
            $files_purge[] = HTTP_PROTOCOL.'://'.STATIC_DOMAIN."/animes/$anime_id/wallpaper_medium.jpg";

            //wallpaper 640 x 360
            $datos  = array(
               'original' => $_FILES['IMG_wallpaper']['tmp_name'],
               'contenedor' => 'animes',
               'blob' => "$anime_id/wallpaper_small.jpg",
               'resolucion' => array(640,360),
               'calidad' => 100
            );
            miniaturas($datos);
            $files_purge[] = HTTP_PROTOCOL.'://'.STATIC_DOMAIN."/animes/$anime_id/wallpaper_small.jpg";

            //purgar
            purge($files_purge);
}//fin de if si se envio la wallpaper.

/* >>>> FIN DE IMAGENES <<<< */


//si llego hasta aqui es que las imagenes se actualizaron correctamente, ahora se hare update al anime por SQL.

//abrir SQL
$mysqli = new mysqli(HOST, USER, PASSWORD, DATABASE);
if($mysqli->connect_errno){ //Fallo la conexión a SQL
    error("No se ha podido conectar con la base de datos.", 500);
}

     //preparar insert
     $prep_stmt = "UPDATE animes SET status = ?, title = ?, slug = ?, simulcasts = ?, sinopsis = ?, emision = ?, nextEpi = ?, collection = ?, message = ? WHERE id = ? LIMIT 1;"; //id es el del anime.
     $stmt = $mysqli->prepare($prep_stmt);

     $anime_slug = slug_generate( $_POST['title'] ); //generar slug a partir del title.

     $stmt->bind_param('ississsisi', $_POST['status'], $_POST['title'], $anime_slug, $_POST['simulcasts'], $_POST['sinopsis'], $_POST['emision'], $_POST['nextEpi'], $_POST['collection'], $_POST['message'], $anime_id);
     $stmt->execute(); //ejecutar consulta.

     $update_exitoso = $stmt->affected_rows; //numero de rilas afectadas, si es 1 es que es exitoso, 0 es que fue fallido.

$stmt->close(); //cerrar sentencia
$mysqli->close(); //cerrar sql

if( $update_exitoso ){//el update fue correcto.
  respuesta_ok( array( 'message' => 'Se ha actualizado correctamente.' ) , 200);
}else{
      error('Ocurrio un error al actualizar el anime.', 400);
    }


}//fin de updateAnime.


//evaluar con que function responder.
if( is_numeric($_GET['value']) ){
  //es numerico el id, entonces se esta haciendo un update.
  updateAnime();
}else{
  //no es numerico, entonces se esta agregando otro anime.
  createAnime();
}
