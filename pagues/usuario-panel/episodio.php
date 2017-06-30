<?php


//requerido ser admin
adminValidate();


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


?>

<div class="returnPanelHome">

  <a href="/panel"><i class="fa fa-reply" aria-hidden="true"></i> Panel</a>

</div>

<?php

//inicio de peticion GET
$http_method=$_SERVER["REQUEST_METHOD"];
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

if($_GET["q"] AND !$_GET["parentId"]){
return " a.title LIKE  '%" . $_GET["q"] . "%'  ";
}else if($_GET["q"] AND $_GET["parentId"] AND is_numeric($_GET["parentId"])  ){

return " a.title LIKE  '%" . $_GET["q"] . "%' AND a.parentId = ". $_GET["parentId"] ." ";

}else if(!$_GET["q"] AND $_GET["parentId"] AND is_numeric($_GET["parentId"]) ){


return " a.parentId = ". $_GET["parentId"] ." ";

}else{
return 1;
}

}


    $prep_stmt = "SELECT a.id, a.status, a.title, a.slug, a.numEpi, a.parentId FROM episodios as a WHERE " . buscar() . " ORDER BY a.id desc LIMIT 20 OFFSET ". offset() ." ;";
    $stmt = $mysqli->prepare($prep_stmt);


    $stmt->execute();
    $stmt->store_result();

    //asignar valores recibidos
    $stmt->bind_result($id, $status, $title, $slug, $numEpi, $parentId);
    


?>

<div class="buscadorPanel">
<form method="GET" action="/panel/episodio">
<input type="text" name="q" placeholder="Buscar..." value="<?php   if($_GET["q"]){ echo $_GET["q"]; }    ?>"/>

<?php
  if($_GET["parentId"]){

  echo '<input type="hidden" value="'. $_GET["parentId"] .'" name="parentId"/>';

  } 
?>

<input type="submit" value="Buscar"/>

</form>
</div>



<table >
  <tr class="hover">
    <th>#ID</th>
    <th>Titulo</th> 
  </tr>

<?php

            //imprimir episodios
            while( $stmt->fetch() ){
            ?>



  <tr>
    <td><a href="/panel/episodio/<?php echo $id; ?>"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> <?php echo $id; ?></a> </td>
    <td><?php echo $title; ?></td>
  </tr>


            <?php
            }


?>
</table>


<div class="offset">

<div class="return"><a href="/panel/episodio?offset=<?php if(!$_GET["offset"]){ echo 0;}else{ echo $_GET["offset"]-1; }   if($_GET["q"]){ echo "&q=".$_GET["q"];}      if($_GET["parentId"]){ echo "&parentId=".$_GET["parentId"];}     ?>">Anterior</a></div>

<div class="next"><a href="/panel/episodio?offset=<?php if(!$_GET["offset"]){ echo 1;}else{ echo $_GET["offset"]+1; }    if($_GET["q"]){ echo "&q=".$_GET["q"];}    if($_GET["parentId"]){ echo "&parentId=".$_GET["parentId"];}   ?>">Proximo</a></div>


</div>







<?php




}else if($_GET["value"] == "nuevo" and $_GET["parentId"]){



//validar parentId - conectar a base de datos
$mysqli = new mysqli(HOST, USER, PASSWORD, DATABASE);


    $prep_stmt = "SELECT id, title, simulcasts, slug FROM animes WHERE id = ? LIMIT 1;";
    $stmt = $mysqli->prepare($prep_stmt);

    $stmt->bind_param('i', $_GET["parentId"]);
    $stmt->execute();
    $stmt->store_result();

    //asignar valores recibidos
    $stmt->bind_result($id, $title, $simulcasts, $slugAnime);
    $stmt->fetch();





    if($id!=$_GET["parentId"]){ echo 'El anime no existe.';exit(); }


    //cerrar SQL
    $stmt->close();
    $mysqli->close();


?>

<!-- FORMULARIO -->
<div class="formulario">
<div class="x-content">

<form method="POST" id="nuevoAnimeForm" enctype="multipart/form-data">

<input type="hidden" name="parentId" value="<?php echo $id; ?>"/>
<input type="hidden" name="slugAnime" value="<?php echo $slugAnime; ?>"/>

<input type="submit" value="Agregar"/>
<div class="clear"></div>

<br><br>

Anime: <a href="/panel/anime/<?php echo $id; ?>"><?php echo $title; ?></a>

<br><br><br>
Estado:<br>
<select name="status">
  <option value="1" selected>Publicado</option>
  <option value="0">Pendiente</option>
</select>
<br><br>
Titulo: <br><input placeholder="Ejemplo: <?php echo $title; ?>..." maxlength="250" name="title" type="text" required/>
<br><br>

¿Es de un anime en emisión este episodio?
<br>
<select name="simulcasts">
  <option value="1" <?php if($simulcasts == 1){ echo "selected";} ?>>Si</option>
  <option value="0" <?php if($simulcasts == 0){ echo "selected";} ?>>No</option>
</select>
<br><br>


Número de episodio (dejar vacio para automatico): <br><input placeholder="Ejemplo: 12" maxlength="3" name="numEpi" type="number"/>
<br><br>


Path del archivo (carpeta): <br><input placeholder="Ejemplo: naruto-sub-espanol..." maxlength="200" name="streamPath" type="text"/>
<br><br>


Nombre del archivo (sin extensión): <br><input placeholder="Ejemplo: 12" maxlength="200" name="streamFile" type="text"/>
<br><br>

</form>
<br>

Mensaje para el usuario (opcional):<br>
<textarea name="message" maxlength="250" placeholder="Información util para el usuario sobre este episodio..." form="nuevoAnimeForm"></textarea>

</div></div>
<!-- FIN formulario -->

<?php
}//fin value=nuevo
?>
<!-- FIN Formulario de agregar -->


<!-- Borrar anime -->
<?php

if( is_numeric($_GET["value"]) and $_GET["borrar"] == "true" and $_GET["parentId"] ){

//conectar a base de datos
$mysqli = new mysqli(HOST, USER, PASSWORD, DATABASE);

    //borrar de tabla episodios
    $prep_stmt = "DELETE FROM episodios WHERE id = ? LIMIT 1";
    $stmt = $mysqli->prepare($prep_stmt);
    $stmt->bind_param('i', $_GET["value"]);
    $stmt->execute();


    //borrar de tabla files
    $prep_stmt = "DELETE FROM files WHERE id = ? LIMIT 1";
    $stmt = $mysqli->prepare($prep_stmt);
    $stmt->bind_param('i', $_GET["value"]);
    $stmt->execute();


    $stmt->close();
    $mysqli->close();

//borrar imagenes
unlink('/var/www/html/assets/media/episodio-'.$_GET["value"].'_pequena.jpg');
unlink('/var/www/html/assets/media/episodio-'.$_GET["value"].'_grande.jpg');
unlink('/var/www/html/assets/media/episodio-'.$_GET["value"].'_sprite.jpg');
unlink('/var/www/html/assets/media/episodio-'.$_GET["value"].'_sprite.vtt');

//borrar cache del episodio
unlink("/var/www/html/assets/cache/episodio-" .$_GET['value']. "-info.json");

//anime
unlink("/var/www/html/assets/cache/anime-" .$_GET['parentId']. "-info.json");

//stream
unlink("/var/www/html/assets/cache/episodio-" .$_GET['value']. "-stream.json");


//redirreccionar a los views episodios
echo '<script>window.location="/panel/episodio/";</script>';
exit();

}
?>
<!-- Borrar anime -->




<!-- Editar informacion del episiodio -->
<?php
if( is_numeric($_GET["value"]) ){

//conectar a base de datos
$mysqli = new mysqli(HOST, USER, PASSWORD, DATABASE);


    $prep_stmt = "SELECT a.id, a.status, a.title, a.slug, a.numEpi, a.imgCustom, a.parentId, a.simulcasts, a.message, b.title, b.id FROM episodios as a, animes as b WHERE a.id = ? AND a.parentId = b.id LIMIT 1";
    $stmt = $mysqli->prepare($prep_stmt);

    $stmt->bind_param('i', $_GET["value"]);
    $stmt->execute();
    $stmt->store_result();

    //asignar valores recibidos
    $stmt->bind_result($id, $status, $title, $slug, $numEpi, $imgCustom, $parentId, $simulcasts, $message, $titleAnime, $idAnime);
    $stmt->fetch();

    if($id!=$_GET["value"]){ echo '<div class="errorLogin"><span><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> El episodio no existe</span></div>'; exit(); }





    //cerrar SQL
    $stmt->close();
    $mysqli->close();
?>



<!-- FORMULARIO -->
<div class="formulario">
<div class="x-content">




<form method="POST" id="nuevoAnimeForm" enctype="multipart/form-data">
<input type="hidden" name="parentId" value="<?php echo $idAnime; ?>"/>


<input type="submit" value="Actualizar"/>
<div class="clear"></div>

<br><br>

Anime: <a href="/panel/anime/<?php echo $idAnime; ?>"><?php echo $titleAnime; ?></a>

<br><br><br>
Estado:<br>
<select name="status">
  <option value="1" <?php if($status==1){ echo 'selected'; }  ?>>Publicado</option>
  <option value="0" <?php if($status==0){ echo 'selected'; }  ?>>Pendiente</option>
</select>
<br><br>
Titulo: <br><input placeholder="Ejemplo: <?php echo $titleAnime; ?>..." maxlength="300" name="title" value="<?php echo $title; ?>" type="text" required/>
<br><br>
¿Es de un anime en emisión este episodio? 
<br>
<select name="simulcasts">
  <option value="1" <?php if($simulcasts==1){ echo 'selected'; }  ?>>Si</option>
  <option value="0" <?php if($simulcasts==0){ echo 'selected'; }  ?>>No</option>
</select>
<br><br>




Número de episodio: <br><input placeholder="Ejemplo: 12..." maxlength="3" name="numEpi" type="number" value="<?php echo $numEpi; ?>" required/>
<br><br>




</form>
<br>
<br>
Mensaje para el usuario (opcional):<br>
<textarea name="message" maxlength="300" placeholder="Información util para el usuario sobre este episodio..." form="nuevoAnimeForm"><?php echo $message; ?></textarea>


<br>

<div class="BorrarBoton">
<a href="/panel/episodio/<?php echo $id; ?>?borrar=true&parentId=<?php echo $idAnime; ?>"><i class="fa fa-trash-o" aria-hidden="true"></i> Borrar</a>
</div>

</div></div>



<!-- FIN formulario -->

<?php
}//fin value=is_numeric
?>
<!-- FIN Editar informacion del anime -->





<!-- HTML GET -->

<?php
}


//inicio peticion POST
else if($http_method=="POST" and $_GET["value"]=="nuevo"){


//conectar a base de datos
$mysqli = new mysqli(HOST, USER, PASSWORD, DATABASE);


    



    //preparar valores
    $status = $_POST["status"];
    $title = $_POST["title"];
    $parentId = $_POST["parentId"];
    $simulcasts = $_POST["simulcasts"];
    $message = $_POST["message"];


//num epi
if( isset($_POST["numEpi"]) AND is_numeric($_POST["numEpi"]) ){
 $numEpi = $_POST["numEpi"];

}else{

//obtener numEpi automatico 


    $prep_stmt = "SELECT numEpi FROM episodios WHERE parentId = ? ORDER BY numEpi desc LIMIT 1;";
    $stmt = $mysqli->prepare($prep_stmt);

    $stmt->bind_param('i', $_GET["parentId"]);
    $stmt->execute();
    $stmt->store_result();

    //asignar valores recibidos
    $stmt->bind_result($numEpi);
    $stmt->fetch();

if($numEpi){
$numEpi=$numEpi+1;
}else{
$numEpi=1;
}

}
   


//slug
$slug = slug_generate($title);

    //ejecutar SQL INSERT en episodios
    $prep_stmt = "INSERT INTO episodios  (status, title, slug, numEpi, parentId, simulcasts, message) VALUES (?, ?, ?, ?, ?, ?, ?);";
    $stmt = $mysqli->prepare($prep_stmt);

    $stmt->bind_param('issiiis', $status, $title, $slug, $numEpi, $parentId, $simulcasts, $message);
    $stmt->execute();
    $id=$stmt->insert_id;


//asignar path de archivo
if( $_POST["streamPath"] AND $_POST["streamFile"] ){
//datos enviados por el admin

$filePath=$_POST["streamPath"];
$fileName=$_POST["streamFile"];

}else if($numEpi == 1){


    $fileName=$numEpi;
    $filePath=$slug;



}else{

    //obtener path anterior
    $prep_stmt = "SELECT a.path FROM stream as a, episodios as b WHERE b.parentId = ? AND b.id = a.id ORDER BY b.id  desc LIMIT 1;";
    $stmt = $mysqli->prepare($prep_stmt);

    $stmt->bind_param('i', $_GET["parentId"]);
    $stmt->execute();
    $stmt->store_result();

    //asignar valores recibidos
    $stmt->bind_result($filePath);
    $stmt->fetch();
    $fileName=$numEpi;

}


    //insertar en tabla stream
    $prep_stmt = "INSERT INTO stream  (id, path, file) VALUES (?, ?, ?);";
    $stmt = $mysqli->prepare($prep_stmt);
    $stmt->bind_param('iss', $id, $filePath, $fileName);
    $stmt->execute();

    //insertar en tabla files
    $prep_stmt = "INSERT INTO files (id) VALUES (?);";
    $stmt = $mysqli->prepare($prep_stmt);
    $stmt->bind_param('i', $id);


    if( $stmt->execute() ){ echo '<script>window.location="/panel/episodio/' .$id. '";</script>';  }else{ echo 'Error al agregar episodio.'; }


    $stmt->close();
    $mysqli->close();
//fin conectar base de datos


//borrar cache del anime
unlink("/var/www/html/assets/cache/anime-" .$_GET['parentId']. "-info.json");



?>


<!-- HTML POST -->



<?php
}else if($http_method=="POST" and is_numeric($_GET["value"]) ){

//acutalizar episodio

//conectar a base de datos
$mysqli = new mysqli(HOST, USER, PASSWORD, DATABASE);


    $prep_stmt = "UPDATE episodios SET status=?, title=?, slug=?, numEpi=?, simulcasts=?, message=? WHERE id = ? LIMIT 1";
    $stmt = $mysqli->prepare($prep_stmt);



    //preparar valores
    $status = $_POST["status"];
    $title = $_POST["title"];
    $simulcasts = $_POST["simulcasts"];
    $numEpi = $_POST["numEpi"];
    $message = $_POST["message"];

$slug = slug_generate($title);

    //ejecutar SQL UPDATE
    $stmt->bind_param('issiisi', $status, $title, $slug, $numEpi, $simulcasts, $message, $_GET["value"]);
    $stmt->execute();









    //cerrar SQL
    $stmt->close();
    $mysqli->close();
    

    echo '<script>location.href = location.href;</script>';

//borrar cache del anime
unlink("/var/www/html/assets/cache/anime-" .$_POST['parentId']. "-info.json");

//stream
unlink("/var/www/html/assets/cache/episodio-" .$_GET['value']. "-stream.json");

//info
unlink("/var/www/html/assets/cache/episodio-" .$_GET['value']. "-info.json");


//cerrar
exit();



}//fin de actualizar
?>







