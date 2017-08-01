<?php
//borrar el anime.

//asignar la id del anime
$anime_id = $_GET['value'];
if( !is_numeric($anime_id) ){
  //la id es invalida.
  error('Ingrese un ID valido.', 400);
}


//abrir SQL
$mysqli = new mysqli(HOST, USER, PASSWORD, DATABASE);
if($mysqli->connect_errno){ //Fallo la conexión a SQL
    error("No se ha podido conectar con la base de datos.", 500);
}

     //preparar insert
     $prep_stmt = "DELETE FROM animes WHERE id = ? LIMIT 1;"; //id es el del anime.
     $stmt = $mysqli->prepare($prep_stmt);

     $stmt->bind_param('i', $anime_id);
     $stmt->execute(); //ejecutar consulta.

     $delete_exitoso = $stmt->affected_rows; //numero de rilas afectadas, si es 1 es que es exitoso, 0 es que fue fallido.

$stmt->close(); //cerrar sentencia
$mysqli->close(); //cerrar sql

if( $delete_exitoso ){//el borrado fue correcto.

  //borrar imagenes de azure.
  delete_azure('animes', "$anime_id/banner_full.jpg");
  delete_azure('animes', "$anime_id/banner_small.jpg");
  delete_azure('animes', "$anime_id/miniature.jpg");
  delete_azure('animes', "$anime_id/wallpaper_full.jpg");
  delete_azure('animes', "$anime_id/wallpaper_medium.jpg");
  delete_azure('animes', "$anime_id/wallpaper_small.jpg");



  respuesta_ok( array( 'message' => 'Se ha borrado correctamente.' ) , 200);
}else{
      error('Ocurrio un error al borrar el anime.', 400);
    }
