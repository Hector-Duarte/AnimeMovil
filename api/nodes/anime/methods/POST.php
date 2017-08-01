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


//cargar imagenes a azure
/*
resoluciones:
                IMG_portada => 240 x 360
                IMG_portada => 90 x 90
                IMG_wallpaper => 1280 x 720
                IMG_wallpaper => 848 x 480
                IMG_wallpaper => 640 x 360
*/


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
