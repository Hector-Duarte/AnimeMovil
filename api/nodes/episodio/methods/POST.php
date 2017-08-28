<?php

//validar session
checkSession('API', true); //'API' es el tipo de callback y el true es que tiene que ser admin.

function createEpisodio(){//funcion para crear nuevos animes.


//verificar que se mandaron todos los datos
if( !isset($_POST['status'], $_POST['message'], $_POST['title'], $_POST['simulcasts'], $_POST['parentId'], $_POST['numEpi'] ) ){
  error('Por favor ingrese todos los datos necesarios y vuelva a intentar.', 400);
}




//abrir SQL
$mysqli = new mysqli(HOST, USER, PASSWORD, DATABASE);
if($mysqli->connect_errno){ //Fallo la conexión a SQL
    error("No se ha podido conectar con la base de datos.", 500);
}

     //preparar insert
     $prep_stmt = "INSERT INTO episodios(status, title, slug, numEpi, imgCustom, parentId, message, simulcasts) VALUES ( ?, ?, ?, ?, 0, ?, ?, ?);"; //id es el del anime.
     $stmt = $mysqli->prepare($prep_stmt);

     $episodio_slug = slug_generate( $_POST['title'] );

     $stmt->bind_param('issiisi', $_POST['status'], $_POST['title'], $episodio_slug, $_POST['numEpi'], $_POST['parentId'], $_POST['message'], $_POST['simulcasts']);
     $stmt->execute(); //ejecutar consulta.

     $episodio_id_new_create = $stmt->insert_id; //ID del anime nuevo.

$stmt->close(); //cerrar sentencia
$mysqli->close(); //cerrar sql

if( !$episodio_id_new_create ){//si el ID no es valido
      error('Ocurrio un error al agregar el episodio.', 400);
    }



//responder con el ID del anime nuevo para hacer redicción.
respuesta_ok( array( "id" => $episodio_id_new_create ) , 201);

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
      error('No se ha detectado algún cambio en la DB, si cargo imagenes nuevas nada más. fue correcta la solicitud.', 202);
    }


}//fin de updateAnime.


//evaluar con que function responder.
if( is_numeric($_GET['value']) ){
  //es numerico el id, entonces se esta haciendo un update.
  updateAnime();
}else{
  //no es numerico, entonces se esta agregando otro anime.
  createEpisodio();
}
