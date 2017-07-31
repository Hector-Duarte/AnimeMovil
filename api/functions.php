<?php

/*** functions de la API. ***/



//preparar firma para acceder a azure

function getSASForBlob($accountName,$container, $blob, $resourceType, $permissions, $expiry,$key)
 {

 /* Create the signature */
 $_arraysign = array();
 $_arraysign[] = $permissions;
 $_arraysign[] = '';
 $_arraysign[] = $expiry;
 $_arraysign[] = '/' . $accountName . '/' . $container . '/' . $blob;
 $_arraysign[] = '';
 $_arraysign[] = "2014-02-14"; //the API version is now required
 $_arraysign[] = '';
 $_arraysign[] = '';
 $_arraysign[] = '';
 $_arraysign[] = '';
 $_arraysign[] = '';

 $_str2sign = implode("\n", $_arraysign);

 return base64_encode(
 hash_hmac('sha256', urldecode(utf8_encode($_str2sign)), base64_decode($key), true)
 );
 }

//generar url de acceso
function getBlobUrl($accountName,$container,$blob,$resourceType,$permissions,$expiry,$_signature)
 {
 /* Create the signed query part */
 $_parts = array();
 $_parts[] = (!empty($expiry))?'se=' . urlencode($expiry):'';
 $_parts[] = 'sr=' . $resourceType;
 $_parts[] = (!empty($permissions))?'sp=' . $permissions:'';
 $_parts[] = 'sig=' . urlencode($_signature);
 $_parts[] = 'sv=2014-02-14';

 /* Create the signed blob URL */
 $_url = 'https://'
 .$accountName.'.blob.core.windows.net/'
 . $container . '/'
 . $blob . '?'
 . implode('&', $_parts);

 return $_url;
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
system("convert ".$i['original']." -coalesce -repage 0x0 -resize ".$miniatura_alto."x".$miniatura_ancho." -layers Optimize ".$i['original']);
}

//convertir
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

ob_start(); // start a new output buffer
imagejpeg($lienzo, NULL, $i['calidad']);
$imagen_data = ob_get_contents();
ob_end_clean(); // stop this output buffer

//preparar acceso a azure
$account_name="ammedia"; //cuenta
$container_name=$i['contenedor']; //contenedor
$blob_name=$i['blob']; //blob
$end_date=gmdate("Y-m-d\TH:i:s\Z", time()+60); //expiracion
$api_key="wxtwiD8SAKd9CGJqN2OM4Pa1ADeyMZHe1io85/fg0njn28Gy67JLbDLU496nWEfyeAxqr16v449R8pdC9QbJaQ=="; //key api

$_signature = getSASForBlob($account_name,$container_name,$blob_name,'b','w',$end_date,$api_key);
$_blobUrl = getBlobUrl($account_name,$container_name,$blob_name,'b','w',$end_date,$_signature);


//size de la imagen
$imagen_size = strlen($imagen_data);


//cargar imagen a azure
//cache al cdn de un año y cache al usuario de 7 dias
$opts = array(
  'http'=>array(
    'method'=>"PUT",
    'header'=>"x-ms-blob-type:BlockBlob\r\n".
              "Content-Type:image/jpg\r\n".
              "x-ms-blob-cache-control:s-maxage=31536000, max-age=604800\r\n".
              "Content-Length:$imagen_size\r\n",
'content' => $imagen_data
   )
);

$context = stream_context_create($opts);

file_get_contents($_blobUrl, false, $context); //enviar imagen a azure

echo json_encode($http_response_header);

imagedestroy($lienzo);imagedestroy($lienzo_temporal); //destruir datos temporales.

if( strpos($http_response_header[0], '201') ){ //comprueba que el elemento se creo
  return true; //la imagen se guardo correctamente.
}else{
  //el elemento no se creo, retornar error
  error('Ocurrio un error al intentar guardar la imagen.', 500);
}


}


/* USO DE FUNCTION.
           $datos  = array(
           'original' => $_FILES['imgPortada']['tmp_name'],
           'contenedor' => 'contenedor',
           'blob' => 'blob.jpg',
           'resolucion' => array(90,90),
           'calidad' => 100
           );
           miniaturas($datos);

*/

// FIN funcion IMG
