<?php
//vars
require_once("/var/www/html/vars_info.php");

?><!DOCTYPE html>
<html lang="es">
<head>
	<title>Buscador</title>
        <link rel="shortcut icon" href="/assets/webApp/favicon.png" type="image/png"/>
        <meta content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1" name="viewport"/>
        <meta charset="utf-8"/>
        <meta content="AnimeMovil" property="og:site_name"/>
        <meta name="keywords" content="Anime, anime online, anime sub español, anime en celular"/>
        <meta name="robots" content="noindex, nofollow"/>
        <link rel="stylesheet" type="text/css" href="/assets/webApp/reset.css"/>
        <link rel="stylesheet" type="text/css" href="/assets/webApp/anime-indexs.css"/>
</head>
<body>

<!--- CONTENIDO DEL INDEX --->

<div class="regresar"><a href="/" title="Regresar al inicio"><i class="fa fa-reply" aria-hidden="true"></i> Regresar al inicio</a></div>






<form method="GET" action="/anime" id="form">

<input type="text" name="q" placeholder="Escribe un termino..." autofocus/>





<select name="letra">
  <option value="ALL">Todas las letras</option>
  <option value="A">A</option>
  <option value="B">B</option>
  <option value="C">C</option>
  <option value="D">D</option>
  <option value="E">E</option>
  <option value="F">F</option>
  <option value="G">G</option>
  <option value="H">H</option>
  <option value="I">I</option>
  <option value="J">J</option>
  <option value="K">K</option>
  <option value="L">L</option>
  <option value="N">N</option>
  <option value="O">O</option>
  <option value="P">P</option>
  <option value="Q">Q</option>
  <option value="R">R</option>
  <option value="S">S</option>
  <option value="T">T</option>
  <option value="U">U</option>
  <option value="V">V</option>
  <option value="W">W</option>
  <option value="X">X</option>
  <option value="Y">Y</option>
  <option value="Z">Z</option>
</select>






<select name="genero">
  <option value="ALL">Todos los generos</option>
  <option value="0">Harem</option>
  <option value="1">Acción</option>
  <option value="2">Comedia</option>
  <option value="3">Colegial</option>
  <option value="4">Mecha</option>
  <option value="5">Cocina</option>
  <option value="6">Misterio</option>
  <option value="7">Deportes</option>
  <option value="8">Fantasía</option>
  <option value="9">Drama</option>
  <option value="10">Romance</option>
  <option value="11">Ecchi</option>
  <option value="12">Horror</option>
  <option value="13">Shounen</option>
  <option value="14">Aventura</option>
  <option value="15">Historico</option>
  <option value="16">Magia</option>
  <option value="17">Música</option>
  <option value="18">Juegos</option>
  <option value="19">Yuri</option>
  <option value="20">Yaoi</option>
  <option value="21">Sobrenatural</option>
</select>


<select name="estado">
  <option value="2">Todos</option>
  <option value="1">En emisión</option>
  <option value="0">Finalizado</option>
</select>




<select name="orden">
  <option value="0">Orden por defecto</option>
  <option value="1">Valoración</option>
  <option value="2">Recientemente agregados</option>
  <option value="3">Los mas antiguos</option>
  <option value="4">De la A a la Z</option>
  <option value="4">De la Z a la A</option>
</select>




<button type="submit" form="form"><i class="fa fa-search" aria-hidden="true"></i> Buscar</button>
</form>



<div class="textHover"><span><i class="fa fa-th-large" aria-hidden="true"></i> Resultados de busqueda</span></div>










<?php


//conectar a base de datos
$mysqli = new mysqli(HOST, USER, PASSWORD, DATABASE);

//consulta para cadena 
function sql_like(){
    if( $_GET["q"] AND strlen( $_GET["q"] ) <= 70  ){
    $text = str_replace(' ', '-', $_GET["q"]);
    $text = preg_replace("/[^A-Za-z0-9\-]/", "", $text) ;
    $text = " a.title LIKE '%". $text ."%' ";
    $text = str_replace('-', '%', $text);
    return $text;
    }else{
          return 1;
          }
}


function sql_offset(){
    if( is_numeric($_GET["offset"]) ){
    $text = $_GET["offset"];
    $text = $text*15;
    return $text;
    }else{
          return 0;
          }
}

function sql_letra(){
    if($_GET["letra"] AND $_GET["letra"] != "ALL" AND strlen($_GET["letra"]) == 1 ){
    $text = ereg_replace("[^A-Z]", "", $_GET["letra"]);
    $text = " a.title LIKE '". $text ."%' ";
    return $text;
    }else{
          return 1;
          }
}

function sql_genero(){
    if($_GET["genero"] AND is_numeric($_GET["genero"]) ){
    $text = $_GET["genero"];
    $text = " b.idGenero = ". $text ." AND a.id = b.idAnime ";
    return $text;
    }else{
          return 1;
          }
}

function sql_estado(){
    if( is_numeric($_GET["estado"]) AND $_GET["estado"] < 2 ){
    $text = $_GET["estado"];
    $text = " a.simulcasts = ". $text ." ";
    return $text;
    }else{
          return 1;
          }
}


    $prep_stmt = "SELECT DISTINCT a.id, a.title, a.slug FROM animes as a, generos as b WHERE ". sql_like() ." AND ". sql_letra() ." AND ". sql_genero() ." AND ". sql_estado() ." ORDER BY a.id desc LIMIT 20 OFFSET ". sql_offset() ." ;";
    $stmt = $mysqli->prepare($prep_stmt);


    $stmt->execute();
    $stmt->store_result();

    //asignar valores recibidos
    $stmt->bind_result($id, $title, $slug);

?>




















<!-- HOVERS -->

<ul class="hovers">

<?php

$animes_count=0;

while( $stmt->fetch() ){

echo '<li> <a href="/anime/'. $id .'-'. $slug .'" title="'. $title .'"> <img src="/assets/media/anime-'. $id .'_portada.jpg"></img> <span>'. $title .'</span> </a> </li>';

$animes_count+=1;
}


?>






</ul>

<!-- FIN HOVERS -->


<?php
if($animes_count == 0){
?>
<div class="error"><span><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> Sin resultados</span></div>
<?php
}
?>

<!-- NAVEGACION -->

<div class="navegacion">

<?php
if(is_numeric($_GET["offset"]) AND $_GET["offset"] > 0){
?>
<div class="anterior"><a href="/anime?offset=<?php echo $_GET["offset"]-1;   if($_GET["q"]){ echo "&q=".str_replace(" ", "+", ereg_replace("[^A-Za-z0-9][+]", "", $_GET["q"]) ); }    if( strlen($_GET["letra"]) == 1 ){ echo "&letra=".ereg_replace("[^A-Z]", "", $_GET["letra"]);  }    if( is_numeric($_GET["genero"]) ){ echo "&genero=".$_GET["genero"];  }   if( is_numeric($_GET["estado"]) ){ echo "&estado=".$_GET["estado"];  }  if( is_numeric($_GET["orden"]) ){ echo "&orden=".$_GET["orden"];  } ?>" title="Proximos titulos"><i class="fa fa-arrow-left" aria-hidden="true"></i> Anterior</a></div>
<?php
}
?>



<?php
if( $animes_count == 19){
?>
<div class="siguiente"><a href="/anime?offset=<?php echo $_GET["offset"]+1;   if($_GET["q"]){ echo "&q=".str_replace(" ", "+", ereg_replace("[^A-Za-z0-9][+]", "", $_GET["q"]) ); }    if( strlen($_GET["letra"]) == 1 ){ echo "&letra=".ereg_replace("[^A-Z]", "", $_GET["letra"]);  }    if( is_numeric($_GET["genero"]) ){ echo "&genero=".$_GET["genero"];  }   if( is_numeric($_GET["estado"]) ){ echo "&estado=".$_GET["estado"];  }  if( is_numeric($_GET["orden"]) ){ echo "&orden=".$_GET["orden"];  } ?>" title="Titulos anteriores">Siguiente <i class="fa fa-arrow-right" aria-hidden="true"></i></a></div>
<?php
}
?>

</div>

<!-- FIN NAVEGACION -->




<!--- FIN CONTENIDO DEL INDEX --->


      
      <script>
      //Funciones necesarias



      }


      </script>

<script async src="/assets/webApp/app.js"></script>
<link href="/assets/webApp/icons/font-awesome.css" rel="stylesheet"/>

</body>
</html>