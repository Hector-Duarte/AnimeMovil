<?php




//requerido ser admin
adminValidate();


//inicio de peticion method
$http_method=$_SERVER["REQUEST_METHOD"];


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

// FIN funcion IMG


?>

<div class="returnPanelHome">

  <a href="/panel"><i class="fa fa-reply" aria-hidden="true"></i> Panel</a>

</div>


<?php


if($http_method=="GET"){
?>
<!-- HTML GET -->





<!-- Formulario de agregar -->
<?php
if(!$_GET["value"]){

//mostrar episodios


//conectar a base de datos
$mysqli = new mysqli(HOST, USER, PASSWORD, DATABASE);


function offset(){if($_GET["offset"]){ return 20*$_GET["offset"]; }else{ return 0; } }

function buscar(){

if($_GET["q"]){
return " a.title LIKE  '%" . $_GET["q"] . "%'  ";
}else{
return 1;
}

}

function simulcasts(){

if($_GET["simulcasts"] == "1"){
return " a.simulcasts = " . 1;
}else if($_GET["simulcasts"] == "0"){
return " a.simulcasts = " . 0;
}else{
    return 1;
}

}



    $prep_stmt = "SELECT a.id, a.title, a.slug FROM animes as a WHERE " . buscar() . " AND ". simulcasts() ." ORDER BY a.id desc LIMIT 20 OFFSET ". offset() ." ;";
    $stmt = $mysqli->prepare($prep_stmt);


    $stmt->execute();
    $stmt->store_result();

    //asignar valores recibidos
    $stmt->bind_result($id, $title, $slug);
    


?>

<div class="buscadorPanel">
<form method="GET" action="/panel/anime">
<input type="text" name="q" placeholder="Buscar..." value="<?php   if($_GET["q"]){ echo $_GET["q"]; }    ?>"/>

<select name="simulcasts">
  <option value="2" selected>Sin filtro</option>
  <option value="1">Emision</option>
  <option value="0">Finalizado</option>
</select>

<input type="submit" value="Buscar"/>

</form>
</div>



<table >
  <tr class="hover">
    <th>#ID</th>
    <th>Agregar</th>
    <th>Titulo</th> 
  </tr>

<?php

            //imprimir episodios
            while( $stmt->fetch() ){
            ?>



  <tr>
    <td><a href="/panel/anime/<?php echo $id; ?>"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> <?php echo $id; ?></a> </td>
    <td><a href="/panel/episodio/nuevo?parentId=<?php echo $id; ?>"> <i class="fa fa-plus" aria-hidden="true"></i></a> </td>
    <td><?php echo $title; ?></td>
  </tr>


            <?php
            }


?>
</table>


<div class="offset">

<div class="return"><a href="/panel/anime?offset=<?php if(!$_GET["offset"]){ echo 0;}else{ echo $_GET["offset"]-1; }   if($_GET["q"]){ echo "&q=".$_GET["q"];}    if($_GET["simulcasts"]){ echo "&simulcasts=".$_GET["simulcasts"];}    ?>">Anterior</a></div>

<div class="next"><a href="/panel/anime?offset=<?php if(!$_GET["offset"]){ echo 1;}else{ echo $_GET["offset"]+1; }    if($_GET["q"]){ echo "&q=".$_GET["q"];}     if($_GET["simulcasts"]){ echo "&simulcasts=".$_GET["simulcasts"];}   ?>">Proximo</a></div>


</div>







<?php




}else if($_GET["value"] == "nuevo"){
?>

<!-- FORMULARIO -->
<div class="formulario">
<div class="x-content">

<form method="POST" id="nuevoAnimeForm" enctype="multipart/form-data">

<input type="submit" value="Publicar"/>
<div class="clear"></div>

<br><br>

Estado:<br>
<select name="status">
  <option value="1" selected>Publicado</option>
  <option value="0">Pendiente</option>
</select>
<br><br>
Titulo: <br><input placeholder="Noragami..." maxlength="300" name="title" type="text" required/>
<br><br>
¿Esta aún en emisión el anime? 
<br>
<select name="simulcasts">
  <option value="1">En emisión</option>
  <option value="0" selected>Finalizado</option>
</select>
<br><br>
Fecha de emisión: <br><input maxlength="35" placeholder="5 de abril de 2015..." name="emision" type="text" required/>

<br><br>
Fecha de proximo episodio (opcional): <br><input maxlength="35" placeholder="Sabado 15 de mayo..." name="nextEpi" type="text"/>
<br><br>
ID de coleección (opcional): <br><input placeholder="532..." name="collection" type="number"/>
<br><br>
Imagen de portada: <br><input type="file" name="imgPortada" accept="image/*" required/>
<br><br>
Imagen grande: <br><input type="file" name="imgGrande" accept="image/*" required/>


<br><br>
Generos: <br>
<input name="generos[]" type="checkbox" value="0">Harem</input>
<input name="generos[]" type="checkbox" value="1">Acción</input>
<input name="generos[]" type="checkbox" value="2">Comedia</input>
<input name="generos[]" type="checkbox" value="3">Colegial</input>
<input name="generos[]" type="checkbox" value="4">Mecha</input>
<input name="generos[]" type="checkbox" value="5">Cocina</input>
<input name="generos[]" type="checkbox" value="6">Misterio</input>
<input name="generos[]" type="checkbox" value="7">Deportes</input>
<input name="generos[]" type="checkbox" value="8">Fantasía</input>
<input name="generos[]" type="checkbox" value="9">Drama</input>
<input name="generos[]" type="checkbox" value="10">Romance</input>
<input name="generos[]" type="checkbox" value="11">Ecchi</input>
<input name="generos[]" type="checkbox" value="12">Horror</input>
<input name="generos[]" type="checkbox" value="13">Shounen</input>
<input name="generos[]" type="checkbox" value="14">Aventura</input>
<input name="generos[]" type="checkbox" value="15">Historico</input>
<input name="generos[]" type="checkbox" value="16">Magia</input>
<input name="generos[]" type="checkbox" value="17">Música</input>
<input name="generos[]" type="checkbox" value="18">Juegos</input>
<input name="generos[]" type="checkbox" value="19">Yuri</input>
<input name="generos[]" type="checkbox" value="20">Yaoi</input>
<input name="generos[]" type="checkbox" value="21">Sobrenatural</input>




</form>
<br>
Sinopsis:<br>
<textarea name="sinopsis" maxlength="3000" placeholder="Este anime trata de ¿?..." form="nuevoAnimeForm"></textarea>
<br><br>
Mensaje para el usuario (opcional):<br>
<textarea name="message" maxlength="300" placeholder="Información util para el usuario sobre este episodio..." form="nuevoAnimeForm"></textarea>

</div></div>
<!-- FIN formulario -->

<?php
}//fin value=nuevo
?>
<!-- FIN Formulario de agregar -->


<!-- Borrar anime -->
<?php

if( is_numeric($_GET["value"]) and $_GET["borrar"] == "true" ){

//conectar a base de datos
$mysqli = new mysqli(HOST, USER, PASSWORD, DATABASE);

    //borrar de tabla animes
    $prep_stmt = "DELETE FROM animes WHERE id = ? LIMIT 1";
    $stmt = $mysqli->prepare($prep_stmt);
    $stmt->bind_param('i', $_GET["value"]);
    $stmt->execute();


    //borrar de tabla generos
    $prep_stmt = "DELETE FROM generos WHERE idAnime = ?";
    $stmt = $mysqli->prepare($prep_stmt);
    $stmt->bind_param('i', $_GET["value"]);
    $stmt->execute();


    //borrar de episodios del anime
    $prep_stmt = "DELETE episodios, stream  FROM episodios  INNER JOIN stream  
    WHERE episodios.id = stream.id and episodios.parentId = ?;";



    $stmt = $mysqli->prepare($prep_stmt);
    $stmt->bind_param('i', $_GET["value"]);
    $stmt->execute();



    $stmt->close();
    $mysqli->close();

//borrar imagenes
unlink('/var/www/html/assets/media/anime-'.$_GET["value"].'_pequena.jpg'); //90x90
unlink('/var/www/html/assets/media/anime-'.$_GET["value"].'_grande.jpg'); //1280x720
unlink('/var/www/html/assets/media/anime-'.$_GET["value"].'_grande-pequena.jpg'); //160x90
unlink('/var/www/html/assets/media/anime-'.$_GET["value"].'_portada.jpg');//225x318

//borrar cache
unlink("/var/www/html/assets/cache/anime-" .$_GET["value"]. "-info.json");

//redirreccionar a los views animes
echo '<script>window.location="/panel/anime/";</script>';
exit();

}
?>
<!-- Borrar anime -->




<!-- Editar informacion del anime -->
<?php
if( is_numeric($_GET["value"]) ){

//conectar a base de datos
$mysqli = new mysqli(HOST, USER, PASSWORD, DATABASE);


    $prep_stmt = "SELECT id, status, title, slug, simulcasts, sinopsis, emision, nextEpi, collection, message FROM animes WHERE id = ? LIMIT 1";
    $stmt = $mysqli->prepare($prep_stmt);

    $stmt->bind_param('i', $_GET["value"]);
    $stmt->execute();
    $stmt->store_result();

    //asignar valores recibidos
    $stmt->bind_result($id, $status, $title, $slug, $simulcasts, $sinopsis, $emision, $nextEpi, $collection, $message);
    $stmt->fetch();

    if($id!=$_GET["value"]){ echo '<div class="errorLogin"><span><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> El anime no existe</span></div>'; exit(); }


    //generos
    $prep_stmt = "SELECT idGenero FROM generos WHERE idAnime = ? LIMIT 30";
    $stmt = $mysqli->prepare($prep_stmt);

    $stmt->bind_param('i', $_GET["value"]);
    $stmt->execute();
    $stmt->store_result();

    //asignar valores recibidos
    $stmt->bind_result($idGenero);


    //obtener valores
    $generos=array();
    while( $stmt->fetch() ) {
        $generos[$idGenero]=$idGenero;
    }










    //cerrar SQL
    $stmt->close();
    $mysqli->close();
?>

<!-- FORMULARIO -->
<div class="formulario">
<div class="x-content">

<form method="POST" id="nuevoAnimeForm" enctype="multipart/form-data">

<input type="submit" value="Actualizar"/>
<div class="clear"></div>

<br><br>

Estado:<br>
<select name="status">
  <option value="1" <?php if($status==1){ echo 'selected'; }  ?>>Publicado</option>
  <option value="0" <?php if($status==0){ echo 'selected'; }  ?>>Pendiente</option>
</select>
<br><br>
Titulo: <br><input placeholder="Noragami..." maxlength="300" name="title" value="<?php echo $title; ?>" type="text" required/>
<br><br>
¿Esta aún en emisión el anime? 
<br>
<select name="simulcasts">
  <option value="1" <?php if($simulcasts==1){ echo 'selected'; }  ?>>En emisión</option>
  <option value="0" <?php if($simulcasts==0){ echo 'selected'; }  ?>>Finalizado</option>
</select>
<br><br>
Fecha de emisión: <br><input maxlength="35" placeholder="5 de abril de 2015..." name="emision" value="<?php echo $emision; ?>" type="text" required/>

<br><br>
Fecha de proximo episodio (opcional): <br><input maxlength="35" placeholder="Sabado 15 de mayo..." name="nextEpi" value="<?php echo $nextEpi; ?>" type="text"/>
<br><br>
ID de coleección (opcional): <br><input placeholder="532..." name="collection" value="<?php echo $collection; ?>" type="number"/>
<br><br>
Imagen de portada (selecciona para actualizar): <br><input type="file" name="imgPortada" accept="image/*"/>
<br><br>
Imagen grande (selecciona para actualizar): <br><input type="file" name="imgGrande" accept="image/*"/>



<br><br>
Generos: <br>
<input name="generos[]" type="checkbox" value="0" <?php if($generos["0"] || $generos["0"]=="0"){ echo 'checked'; } ?>>Harem</input>
<input name="generos[]" type="checkbox" value="1" <?php if($generos["1"]){ echo 'checked'; } ?>>Acción</input>
<input name="generos[]" type="checkbox" value="2" <?php if($generos["2"]){ echo 'checked'; } ?>>Comedia</input>
<input name="generos[]" type="checkbox" value="3" <?php if($generos["3"]){ echo 'checked'; } ?>>Colegial</input>
<input name="generos[]" type="checkbox" value="4" <?php if($generos["4"]){ echo 'checked'; } ?>>Mecha</input>
<input name="generos[]" type="checkbox" value="5" <?php if($generos["5"]){ echo 'checked'; } ?>>Cocina</input>
<input name="generos[]" type="checkbox" value="6" <?php if($generos["6"]){ echo 'checked'; } ?>>Misterio</input>
<input name="generos[]" type="checkbox" value="7" <?php if($generos["7"]){ echo 'checked'; } ?>>Deportes</input>
<input name="generos[]" type="checkbox" value="8" <?php if($generos["8"]){ echo 'checked'; } ?>>Fantasía</input>
<input name="generos[]" type="checkbox" value="9" <?php if($generos["9"]){ echo 'checked'; } ?>>Drama</input>
<input name="generos[]" type="checkbox" value="10" <?php if($generos["10"]){ echo 'checked'; } ?>>Romance</input>
<input name="generos[]" type="checkbox" value="11" <?php if($generos["11"]){ echo 'checked'; } ?>>Ecchi</input>
<input name="generos[]" type="checkbox" value="12" <?php if($generos["12"]){ echo 'checked'; } ?>>Horror</input>
<input name="generos[]" type="checkbox" value="13" <?php if($generos["13"]){ echo 'checked'; } ?>>Shounen</input>
<input name="generos[]" type="checkbox" value="14" <?php if($generos["14"]){ echo 'checked'; } ?>>Aventura</input>
<input name="generos[]" type="checkbox" value="15" <?php if($generos["15"]){ echo 'checked'; } ?>>Historico</input>
<input name="generos[]" type="checkbox" value="16" <?php if($generos["16"]){ echo 'checked'; } ?>>Magia</input>
<input name="generos[]" type="checkbox" value="17" <?php if($generos["17"]){ echo 'checked'; } ?>>Música</input>
<input name="generos[]" type="checkbox" value="18" <?php if($generos["18"]){ echo 'checked'; } ?>>Juegos</input>
<input name="generos[]" type="checkbox" value="19" <?php if($generos["19"]){ echo 'checked'; } ?>>Yuri</input>
<input name="generos[]" type="checkbox" value="20" <?php if($generos["20"]){ echo 'checked'; } ?>>Yaoi</input>
<input name="generos[]" type="checkbox" value="21" <?php if($generos["21"]){ echo 'checked'; } ?>>Sobrenatural</input>




<br>
<br>

<img src="/assets/media/anime-<?php echo $id; ?>_portada.jpg<?php echo '?'.rand(); ?>" height="80"></img>
<img src="/assets/media/anime-<?php echo $id; ?>_grande.jpg<?php echo '?'.rand(); ?>" height="80"></img>
<img src="/assets/media/anime-<?php echo $id; ?>_pequena.jpg<?php echo '?'.rand(); ?>" height="80"></img>
<img src="/assets/media/anime-<?php echo $id; ?>_grande-pequena.jpg<?php echo '?'.rand(); ?>" height="80"></img>
</form>
<br>
Sinopsis:<br>
<textarea name="sinopsis" maxlength="3000" placeholder="Este anime trata de ¿?..." form="nuevoAnimeForm"><?php echo $sinopsis; ?></textarea>
<br><br>
Mensaje para el usuario (opcional):<br>
<textarea name="message" maxlength="300" placeholder="Información util para el usuario sobre este episodio..." form="nuevoAnimeForm"><?php echo $message; ?></textarea>



<br>

<div class="BorrarBoton">
<a href="/panel/anime/<?php echo $id; ?>?borrar=true"><i class="fa fa-trash-o" aria-hidden="true"></i> Borrar</a>
</div>


</div></div>
<!-- FIN formulario -->

<?php
}//fin value=is_numeric
?>
<!-- FIN Editar informacion del anime -->





<!-- HTML GET -->

<?php
}//fin de peticion GET

//inicio peticion POST
else if($http_method=="POST" and $_GET["value"]=="nuevo"){


//conectar a base de datos
$mysqli = new mysqli(HOST, USER, PASSWORD, DATABASE);


    

    $prep_stmt = "INSERT INTO animes  (status, title, slug, simulcasts, sinopsis, emision, nextEpi, collection, message) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?);";
    $stmt = $mysqli->prepare($prep_stmt);

    //preparar valores
    $status = $_POST["status"];
    $title = $_POST["title"];
    $simulcasts = $_POST["simulcasts"];
    $sinopsis = $_POST["sinopsis"];
    $emision = $_POST["emision"];
    $nextEpi = $_POST["nextEpi"];
    $collection = $_POST["collection"];
    $message = $_POST["message"];



    //comprobar si se cargaron imagenes
    if( is_null($_FILES['imgPortada']['tmp_name']) || is_null($_FILES['imgGrande']['tmp_name']) ){
    echo "imagenes invalidas";exit();

     }

$slug = slug_generate($title);

    //ejecutar SQL INSERT
    $stmt->bind_param('ississsis', $status, $title, $slug, $simulcasts, $sinopsis, $emision, $nextEpi, $collection, $message);
    if( $stmt->execute() ){ echo '<script>window.location="/panel/anime/' .$stmt->insert_id. '";</script>';  }else{ echo 'Error al agregar anime.'; }
    $id=$stmt->insert_id;





//generos
if( $_POST["generos"] ){

$generos_string=" ";
$generos= $_POST["generos"];
$generos_count=count($generos);


//preparar generos_string
for($i=0; $i<$generos_count ;$i++){

//no poner coma al primer elemento
if($i!=0){ $generos_string.=",";  }


//crear parentecis
$generos_string .= "($id,$generos[$i])";


}//fin for


    $prep_stmt = "INSERT INTO generos  (idAnime, idGenero) VALUES $generos_string  ;";
    $stmt = $mysqli->prepare($prep_stmt);
    $stmt->execute();
}
//FIN generos






    $stmt->close();
    $mysqli->close();
//fin conectar base de datos


//comprobar imagenes






//portada img
$datos  = array(
'original' => $_FILES['imgPortada']['tmp_name'],
'nuevo' => '/var/www/html/assets/media/anime-'.$id.'_portada.jpg',
'resolucion' => array(225,318),
'calidad' => 100
);
miniaturas($datos);

//90x90 img
$datos  = array(
'original' => $_FILES['imgPortada']['tmp_name'],
'nuevo' => '/var/www/html/assets/media/anime-'.$id.'_pequena.jpg',
'resolucion' => array(90,90),
'calidad' => 100
);
miniaturas($datos);


//grande img 1280x720
$datos  = array(
'original' => $_FILES['imgGrande']['tmp_name'],
'nuevo' => '/var/www/html/assets/media/anime-'.$id.'_grande.jpg',
'resolucion' => array(1280,720),
'calidad' => 100
);
miniaturas($datos);

//grande pequena img 160x90
$datos  = array(
'original' => $_FILES['imgGrande']['tmp_name'],
'nuevo' => '/var/www/html/assets/media/anime-'.$id.'_grande-pequena.jpg',
'resolucion' => array(160,90),
'calidad' => 100
);
miniaturas($datos);



?>


<!-- HTML POST -->



<?php
}else if($http_method=="POST" and is_numeric($_GET["value"]) ){

//acutalizar anime

//conectar a base de datos
$mysqli = new mysqli(HOST, USER, PASSWORD, DATABASE);


    $prep_stmt = "UPDATE animes SET status=?, title=?, slug=?, simulcasts=?, sinopsis=?, emision=?, nextEpi=?, collection=?, message=? WHERE id = ? LIMIT 1";
    $stmt = $mysqli->prepare($prep_stmt);



    //preparar valores
    $status = $_POST["status"];
    $title = $_POST["title"];
    $simulcasts = $_POST["simulcasts"];
    $sinopsis = $_POST["sinopsis"];
    $emision = $_POST["emision"];
    $nextEpi = $_POST["nextEpi"];
    $collection = $_POST["collection"];
    $message = $_POST["message"];



    //comprobar si se cargaron imagenes
    if( $_FILES['imgPortada']['tmp_name'] || $_FILES['imgGrande']['tmp_name'] ){
    //inicio imagenes

           
           if($_FILES['imgPortada']['tmp_name']){

           //portada img
           $datos  = array(
           'original' => $_FILES['imgPortada']['tmp_name'],
           'nuevo' => '/var/www/html/assets/media/anime-'.$_GET["value"].'_portada.jpg',
           'resolucion' => array(225,318),
           'calidad' => 100
           );
           miniaturas($datos);

           //90x90 img
           $datos  = array(
           'original' => $_FILES['imgPortada']['tmp_name'],
           'nuevo' => '/var/www/html/assets/media/anime-'.$_GET["value"].'_pequena.jpg',
           'resolucion' => array(90,90),
           'calidad' => 100
           );
           miniaturas($datos);

           }

           if($_FILES['imgGrande']['tmp_name']){

           //grande img 1280x720
           $datos  = array(
           'original' => $_FILES['imgGrande']['tmp_name'],
           'nuevo' => '/var/www/html/assets/media/anime-'.$_GET["value"].'_grande.jpg',
           'resolucion' => array(1280,720),
           'calidad' => 100
           );
           miniaturas($datos);


           //grande pequena img 160x90
           $datos  = array(
           'original' => $_FILES['imgGrande']['tmp_name'],
           'nuevo' => '/var/www/html/assets/media/anime-'.$_GET["value"].'_grande-pequena.jpg',
           'resolucion' => array(160,90),
           'calidad' => 100
           );
           miniaturas($datos);



           } 

    //fin imagenes
     }

$slug = slug_generate($title);

    //ejecutar SQL UPDATE
    $stmt->bind_param('ississsisi', $status, $title, $slug, $simulcasts, $sinopsis, $emision, $nextEpi, $collection, $message, $_GET["value"]);
    $stmt->execute();









//generos
if( $_POST["generos"] ){


    //borrar de tabla generos para insertar nuevos
    $prep_stmt = "DELETE FROM generos WHERE idAnime = ?";
    $stmt = $mysqli->prepare($prep_stmt);
    $stmt->bind_param('i', $_GET["value"]);
    $stmt->execute();






$generos_string=" ";
$generos= $_POST["generos"];
$generos_count=count($generos);

//preparar generos_string
for($i=0; $i<$generos_count ;$i++){

//no poner coma al primer elemento
if($i!=0){ $generos_string.=",";  }


//crear parentecis
$generos_string .= "(".$_GET["value"].",$generos[$i])";


}//fin for


    $prep_stmt = "INSERT INTO generos  (idAnime, idGenero) VALUES $generos_string  ;";
    $stmt = $mysqli->prepare($prep_stmt);
    $stmt->execute();

}else{
//no se enviaron generos, borrar todos

    //borrar de tabla generos para insertar nuevos
    $prep_stmt = "DELETE FROM generos WHERE idAnime = ?";
    $stmt = $mysqli->prepare($prep_stmt);
    $stmt->bind_param('i', $_GET["value"]);
    $stmt->execute();




}
//FIN generos







//borrar cache
unlink("/var/www/html/assets/cache/anime-" .$_GET["value"]. "-info.json");




    //cerrar SQL
    $stmt->close();
    $mysqli->close();
    

    echo '<script>location.href = location.href;</script>';
    exit();



}//fin de actualizar
?>







