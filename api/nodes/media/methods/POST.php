<?php

if( !is_numeric( $_GET['value'] ) ){
	error("ID invalida", 400);
}


//funcion IMG
function miniaturas($a = false) {
//datos por defecto
$i = array('original' => false, 'nuevo' => false, 'resolucion' => false, 'calidad' => 90);

if ($a) {$i = array_replace($i, $a);}


if($i['resolucion'] == 'original') {
$dat = getimagesize($i['original']);
$miniatura_ancho_maximo = $dat[0];
$miniatura_alto_maximo = $dat[1];
} else {
$miniatura_ancho_maximo = is_array($i['resolucion']) ? $i['resolucion'][0] : intval(sitio('img_px')[0]);
$miniatura_alto_maximo = is_array($i['resolucion']) ? $i['resolucion'][1] : intval(sitio('img_px')[1]);
}

$info_imagen = getimagesize($i['original']);
$imagen_ancho = $info_imagen[0];
$imagen_alto = $info_imagen[1];
$imagen_tipo = $info_imagen['mime'];


$proporcion_imagen = $imagen_ancho / $imagen_alto;
$proporcion_miniatura = $miniatura_ancho_maximo / $miniatura_alto_maximo;

if ( $proporcion_imagen > $proporcion_miniatura ){
	$miniatura_ancho = $miniatura_alto_maximo * $proporcion_imagen;
	$miniatura_alto = $miniatura_alto_maximo;
} else if ( $proporcion_imagen < $proporcion_miniatura ){
	$miniatura_ancho = $miniatura_ancho_maximo;
	$miniatura_alto = $miniatura_ancho_maximo / $proporcion_imagen;
} else {
	$miniatura_ancho = $miniatura_ancho_maximo;
	$miniatura_alto = $miniatura_alto_maximo;
}

$x = ( $miniatura_ancho - $miniatura_ancho_maximo ) / 2;
$y = ( $miniatura_alto - $miniatura_alto_maximo ) / 2;

//convertir gif
if($imagen_tipo == 'image/gif') {
system("convert ".$i['original']." -coalesce -repage 0x0 -resize ".$miniatura_alto."x".$miniatura_ancho." -layers Optimize ".$i['nuevo']);
}
//convertir otras
else {
switch ( $imagen_tipo ){
	case "image/jpg":
	case "image/jpeg":
		$imagen = imagecreatefromjpeg( $i['original'] );
		break;
	case "image/png":
		$imagen = imagecreatefrompng( $i['original'] );
		break;
	case "image/gif":
		$imagen = imagecreatefromgif( $i['original'] );
		break;
}

$lienzo = imagecreatetruecolor( $miniatura_ancho_maximo, $miniatura_alto_maximo );
$lienzo_temporal = imagecreatetruecolor( $miniatura_ancho, $miniatura_alto );
//Creamos la imagen
imagecopyresampled($lienzo_temporal, $imagen, 0, 0, 0, 0, $miniatura_ancho, $miniatura_alto, $imagen_ancho, $imagen_alto);
imagecopy($lienzo, $lienzo_temporal, 0,0, $x, $y, $miniatura_ancho_maximo, $miniatura_alto_maximo);
imagejpeg($lienzo, $i['nuevo'], $i['calidad']);
imagedestroy($lienzo);imagedestroy($lienzo_temporal);
}
return true;
}


/*
           $datos  = array(
           'original' => $_FILES['imgPortada']['tmp_name'],
           'nuevo' => '/var/www/html/assets/media/anime-'.$_GET["value"].'_pequena.jpg',
           'resolucion' => array(90,90),
           'calidad' => 100
           );
           miniaturas($datos);

*/

// FIN funcion IMG



if($_FILES['imgCustom'] AND $_FILES['spriteImg'] AND $_FILES['spriteVtt'] AND is_numeric($_GET['value']) ){


           //pequena
           $datos  = array(
           'original' => $_FILES['imgCustom']['tmp_name'],
           'nuevo' => '/var/www/html/assets/media/episodio-'.$_GET["value"].'_pequena.jpg',
           'resolucion' => array(440,250),
           'calidad' => 100
           );
           miniaturas($datos);

           //grande
           $datos  = array(
           'original' => $_FILES['imgCustom']['tmp_name'],
           'nuevo' => '/var/www/html/assets/media/episodio-'.$_GET["value"].'_grande.jpg',
           'resolucion' => array(854,480),
           'calidad' => 100
           );
           miniaturas($datos);


           //imagen sprite
           $datos = file_get_contents( $_FILES['spriteImg']['tmp_name'] );
           file_put_contents('/var/www/html/assets/media/episodio-'.$_GET["value"].'_sprite.jpg', $datos);



           //indice sprite
           $datos = file_get_contents( $_FILES['spriteVtt']['tmp_name'] );
           $datos = str_replace('raw_sprite', 'episodio-'.$_GET["value"].'_sprite', $datos);
           file_put_contents('/var/www/html/assets/media/episodio-'.$_GET["value"].'_sprite.vtt', $datos);





                           //abrir SQL
                           $mysqli = new mysqli(HOST, USER, PASSWORD, DATABASE);


                               $prep_stmt = "UPDATE episodios SET imgCustom = 1 WHERE id = ? LIMIT 1;";
                               $stmt = $mysqli->prepare($prep_stmt);

                               $stmt->bind_param('i', $_GET['value']);

                           if( $stmt->execute() ){
	                           //Se creo  correctamente 
                           	respuesta_ok( array("ok" => true ) ,201);
                           }else{
                           	//no se creo
                               respuesta_ok( array( "ok" => false ) ,200);
                           }



                           //cerar SQL
                           $stmt->close();
                           $mysqli->close();



}else{
	//no se cumplen los requisitos para cargar
	error("Faltan archivos", 400);
}