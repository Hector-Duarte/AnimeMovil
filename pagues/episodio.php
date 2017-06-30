<?php

//vars
require_once("/var/www/html/vars_info.php");


$cachePath = CACHE_PATH . "episodio-" .$_GET['id']. "-info.json";
$cacheStreamPath = CACHE_PATH . "episodio-" .$_GET['id']. "-stream.json";



if( file_exists($cachePath) AND file_exists($cacheStreamPath) ){

//existe cache
header("x-cache: HIT");

//obtener de cache
$cache=json_decode( file_get_contents($cachePath) );
$stream=json_decode( file_get_contents($cacheStreamPath) );

$stream=$stream->activos;

//datos del anime
$id = $cache->episodio->id;
$status = $cache->episodio->status;
$title = $cache->episodio->title;
$slug = $cache->episodio->slug;
$numEpi = $cache->episodio->numEpi;
$imgCustom = $cache->episodio->imgCustom;
$imgSprite = $cache->episodio->imgSprite;
$message = $cache->episodio->message;
$parentId = $cache->episodio->parentId;
$animeSlug = $cache->episodio->animeSlug;

$epiSiguiente = $cache->episodio->siguiente;
$epiAnterior = $cache->episodio->anterior;




//FIN existe cache
}else{
//no existe cache
header("x-cache: MISS");


$cache=new stdClass();

//conectar a base de datos
$mysqli = new mysqli(HOST, USER, PASSWORD, DATABASE);



    $prep_stmt = "SELECT a.id, a.status, a.title, a.slug, a.numEpi, a.imgCustom, a.message, a.parentId, b.slug FROM episodios as a, animes as b WHERE a.id = ? AND a.parentId = b.id AND a.status = 1  LIMIT 1;";
    $stmt = $mysqli->prepare($prep_stmt);

    $stmt->bind_param('i', $_GET["id"]);
    $stmt->execute();
    $stmt->store_result();

    //asignar valores recibidos
    $stmt->bind_result($id, $status, $title, $slug, $numEpi, $imgCustom, $message, $parentId, $animeSlug);
    $stmt->fetch();


$cache->episodio->id = $id;
$cache->episodio->status = $status;
$cache->episodio->title = $title;
$cache->episodio->slug = $slug;
$cache->episodio->numEpi = $numEpi;
$cache->episodio->imgCustom = $imgCustom;
$cache->episodio->imgSprite = $imgSprite;
$cache->episodio->message = $message;
$cache->episodio->parentId = $parentId;
$cache->episodio->animeSlug = $animeSlug;





//obtener episodio siguiente
    $prep_stmt = "SELECT id, slug FROM episodios WHERE parentId = ? AND status = 1 AND numEpi > ?  LIMIT 1;";
    $stmt = $mysqli->prepare($prep_stmt);

    $stmt->bind_param('ii', $parentId, $numEpi);
    $stmt->execute();
    $stmt->store_result();

    //asignar valores recibidos
    $stmt->bind_result($epiSiguienteId, $epiSiguienteSlug);
    $stmt->fetch();

//evaluar si existe cap
if( isset($epiSiguienteSlug) ){
$epiSiguiente=$epiSiguienteId."-".$epiSiguienteSlug;
}else{
$epiSiguiente=false;
}



//obtener episodio anterior
    $prep_stmt = "SELECT id, slug FROM episodios WHERE parentId = ? AND status = 1 AND numEpi < ? ORDER BY numEpi desc LIMIT 1;";
    $stmt = $mysqli->prepare($prep_stmt);

    $stmt->bind_param('ii', $parentId, $numEpi);
    $stmt->execute();
    $stmt->store_result();

    //asignar valores recibidos
    $stmt->bind_result($epiAnteriorId, $epiAnteriorSlug);
    $stmt->fetch();

//evaluar si existe cap
if( isset($epiAnteriorSlug) ){
$epiAnterior=$epiAnteriorId."-".$epiAnteriorSlug;
}else{
$epiAnterior=false;
}



$cache->episodio->siguiente = $epiSiguiente;
$cache->episodio->anterior = $epiAnterior;






   //error 404
   if(!$id){ 
     http_response_code(404);
     include("/var/www/html/errores/404.html");
     exit(); 

      //cerrar sql
      $stmt->close();
      $mysqli->close();
}

//FIN conectar a base de datos


//guardar cache
file_put_contents($cachePath, json_encode($cache));








//cache de stream
//no existe cache

$stream=new stdClass();

    $prep_stmt = "SELECT * FROM stream WHERE id = ? LIMIT 1;";
    $stmt = $mysqli->prepare($prep_stmt);

    $stmt->bind_param('i', $_GET['id']);
    $stmt->execute();

// asociar a su columna
    $meta = $stmt->result_metadata();
    $fields = $meta->fetch_fields();
    foreach($fields as $field) {
        $result[$field->name] = "";
        $resultArray[$field->name] = &$result[$field->name];
    }

    call_user_func_array(array($stmt, 'bind_result'), $resultArray);

    // create object of results and array of objects
        $streamInfo = array();

    while($stmt->fetch()) {
        $resultObject = new stdClass();

        foreach ($resultArray as $key => $value) {


//no retornar id, path y file
if($key != "id" AND $key != "path" AND $key != "file" AND $value != null){

$streamInfo[] = $key;

}


            $resultObject->$key = $value;

        }

        $rows[] = $resultObject;
    }


$stream=$rows;
unset($rows);


//asignar servidores activos
$stream[0]->activos = $streamInfo;

//FIN conectar a base de datos


//guardar cache
file_put_contents($cacheStreamPath, json_encode($stream[0]));

 $stream=$stream[0]->activos;


    //cerrar sql
    $stmt->close();
    $mysqli->close();

}//fin cache no existe





    //comprobar slug
    if($_GET["slug"] != $slug){ header("Location: /episodio/$id-$slug");exit(); }






//Verificar session valida
function validateSession(){

   $_arraysign = array();
   $_arraysign[] = $_COOKIE["session_user_id"]; //id del usuario
   $_arraysign[] = $_COOKIE["session_user_name"]; //nombre de usuario
   $_arraysign[] = $_COOKIE["session_id"]; //session id
   $_arraysign[] = $_COOKIE["session_user_level"]; //nivel de usuario (0=admin && 1=usuario estandar)
   $_arraysign[] = $_COOKIE["session_expire"]; //expiracion
   $_arraysign[] = SIGNATURE_HASH_USER; //key hash

   $_str2sign = implode("\n", $_arraysign);
 
   $session_hash = base64_encode( hash_hmac('sha256', urldecode(utf8_encode($_str2sign)), base64_decode($key), true) ); //hast token para verificar sesion


          if($session_hash === $_COOKIE["session_hash"] AND is_numeric($_COOKIE["session_expire"]) AND $_COOKIE["session_expire"] > time() ){
          return true;
           }else{
          return false;

           }


}

     $sesion_status = validateSession();



?>
<!DOCTYPE html>
<html lang="es">
<head>
	<title><?php echo $title; ?></title>

        <meta content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1" name="viewport"/>
        <meta charset="utf-8"/>
        <meta content="AnimeMovil" property="og:site_name"/>
        <meta name="referrer" content="never"/>
        <meta name="keywords" content="Anime, anime online, anime sub español, anime en celular"/>
        <meta name="robots" content="noindex, nofollow"/>
        <meta content='<?php echo $title; ?> online, Ver cap <?php echo $title; ?> Completo, Descargar <?php echo $title; ?> episodio en celular - Anime Móvil' name='description'/>
        <meta property="fb:app_id" content="356185744523413"/>
        <meta content='<?php echo $title; ?>, ver <?php echo $title; ?>, online móvil, HD Anime Móvil' name='keywords'/>
        <meta content='<?php echo $title; ?> online, Ver cap <?php echo $title; ?> Completo, Descargar <?php echo $title; ?> episodio en celular - Anime Móvil' name='og:description'/>
        <meta content='Anime Móvil' name='Author'/>
        <meta content='general' name='rating'/>
        <meta property="og:title" content="<?php echo $title; ?>"/>
        <meta property="og:type" content="video.episode"/>
        <link rel="shortcut icon" href="/assets/webApp/favicon.png" type="image/png"/>
        <link rel="stylesheet" type="text/css" href="/assets/webApp/app.css"/>

<?php
//img
if($imgCustom == 1){
$imgPath = "/assets/media/episodio-" . $id ."_grande.jpg";
}else{
$imgPath = "/assets/media/anime-" . $parentId ."_grande.jpg";
}

?>

        <link href='<?php echo $imgPath; ?>' rel='image_src'/>
        <meta content='<?php echo $imgPath; ?>' property='og:image'/>






                  <!-- Taboola script -->
                  <script type="text/javascript">
                    window._taboola = window._taboola || [];
                    _taboola.push({article:'auto'});
                    !function (e, f, u, i) {
                      if (!document.getElementById(i)){
                        e.async = 1;
                        e.src = u;
                        e.id = i;
                        f.parentNode.insertBefore(e, f);
                      }
                    }(document.createElement('script'),
                    document.getElementsByTagName('script')[0],
                    '//cdn.taboola.com/libtrc/animemovil/loader.js',
                    'tb_loader_script');
                  </script>





</head>
<body>

<!-- FB script -->
<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/es_LA/sdk.js#xfbml=1&version=v2.8&appId=356185744523413";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>





<header>
 <nav class="contentHeader">  
   <div class="cabecera">

<div class="logo"><a href="/" title="Pagina principal"><img src="/assets/webApp/logo.png"/></a></div>





<div class="navegacion" id="navegacionOptions">

    <button id="navegacionOptionsButton"></button>

  <div class="contenidoNavegacion">
    <a href="/anime" title="Animes"><i class="fa fa-play" aria-hidden="true"></i> Anime</a>
    <a href="/emision" title="Emisiones"><i class="fa fa-calendar-check-o" aria-hidden="true"></i> Emision</a>
  </div>
</div>








<div class="leftNavegacion">
 <div id="buscadorView" class="buscadorNavegacion">

   <button id="buscadorBotonView"></button>

  <form method="get" id="formNav" action="/anime" autocomplete="off">
    <input type="text" name="q" placeholder="Ingresa un termino..." required/>
    <button form="formNav" type="submit"><i class="fa fa-search" aria-hidden="true"></i></button>

 

             <div class="buscadorResultados">
               <ul>
                <!--
                <li><a href="#"><img src="//img.animemovil.com/s90-c/ao-no-exorcist-kyoto-fujouou-hen-sub-espanol.jpg"></img> XXXXXXXXXXXXXXXXXXXXXXXXXX </a></li>
                <li><a href="#"><img src="//img.animemovil.com/s90-c/ao-no-exorcist-kyoto-fujouou-hen-sub-espanol.jpg"></img> XXXXXXXXXXXXXXXXXXXXXXXXXX </a></li>
                <li><a href="#"><img src="//img.animemovil.com/s90-c/ao-no-exorcist-kyoto-fujouou-hen-sub-espanol.jpg"></img> XXXXXXXXXXXXXXXXXXXXXXXXXX </a></li>
                <li><a href="#"><img src="//img.animemovil.com/s90-c/ao-no-exorcist-kyoto-fujouou-hen-sub-espanol.jpg"></img> XXXXXXXXXXXXXXXXXXXXXXXXXX </a></li>
               -->
               </ul>
             </div>


  </form>
 </div>


<div class="usuarioNavegacion" id="usuarioNavegacion">
   <button><i class="fa fa-user-o" aria-hidden="true"></i></button>  
         <div class="menuUsuarioNavegacion">
              <ul>
                <?php
                       if($sesion_status == true){
                        ?>
                               <li><a href="/misAnimes" title="Animes favoritos"><i class="fa fa-star" aria-hidden="true"></i> Favoritos</a></li>
                               <li><a href="/historial" title="Historial"><i class="fa fa-clock-o" aria-hidden="true"></i> Historial</a></li>
                               <li><a href="/perfil" title="Editar perfil"><i class="fa fa-user" aria-hidden="true"></i> Perfil</a></li>
                               <li><a href="/logout" title="Cerrar sesión"><i class="fa fa-sign-out" aria-hidden="true"></i> Cerrar sesión</a></li>
                        <?php
                       }else{
                         ?>
                               <li><a href="/entrar" title="Iniciar Sesión"><i class="fa fa-sign-in" aria-hidden="true"></i> Iniciar Sesión</a></li>
                         <?php
                             }
                ?>
              </ul>
         </div>
</div>


</div>



   </div>
 </nav>
</header>








<!-- Cuerpo de la pagina -->
<div class="cuerpoBase">

<!-- EpisodioCuerpo -->
<div class="EpisodioCuerpo">

<!-- titulo -->
<div class="EpisodioTitulo">
<h1><?php echo $title; ?></h1>






<?php
//firmar reporte
$_arraysign = array();
 $_arraysign[] = $id; //id del cap
 $_arraysign[] = SIGNATURE_HASH; //key hash

 $_str2sign = implode("\n", $_arraysign);
 
$signature = base64_encode(
 hash_hmac('sha256', urldecode(utf8_encode($_str2sign)), base64_decode($key), true)
 );




?>
<div class="reporteEpisodio"><a href="/reporte/<?php echo $id. "?signature=" .$signature; ?>" title="Reportar episodio" target="_blank"><i class="fa fa-flag" aria-hidden="true"></i></a></div>

<div class="clear"></div>

</div>


<!-- Video contenedor -->
<div class="videoEpisodio">
<!-- REPRODUCTOR -->
<div class="videoContent" id="parentPlayer">



<div id="player"></div>


</div>
</div>
<!-- FIN Video contenedor -->



<!-- mensaje del administrador -->
<?php
if( $message ){
?>
<div class="episodioMessage"><span><?php echo $message; ?></span></div>
<?php
}
?>
<!-- FIN mensaje del administrador -->




<!-- Servidores -->

<div class="servidoresVideo">
<div class="x-text"><span>Servidores:</span></div>

<div id="servidorPush"></div>

<ul class="servers" id="servidores">

<li node="akiba">Akiba</li>


<?php

foreach ($stream as $stream_server) {

    echo '<li node="'.$stream_server.'">'.$stream_server.'</li>';
}

?>




</ul>


</div>

<!-- FIN Servidores -->







<!-- FLEX-->
<div class="flex">

<!-- Descargas -->

<div class="descargarEpisodio">

<div class="x-link"> 
<?php
//generar firma para descargar
$expire = time()+21600; //expira en 6 horas

$_arraysign = array();
 $_arraysign[] = $id; //id del cap
 $_arraysign[] = $expire; //expira en 6 horas
  $_arraysign[] = "downloadWeb"; //tipo de callback

   //IP seguridad
  if (isset($_SERVER["HTTP_CF_CONNECTING_IP"])) {
   $_SERVER['REMOTE_ADDR'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
   }
  $_arraysign[] = $_SERVER['REMOTE_ADDR']; //Ip de usuario      
  //FIP IP seguridad

  
 $_arraysign[] = SIGNATURE_HASH; //key hash
 $_str2sign = implode("\n", $_arraysign);
 
$signature = base64_encode(
 hash_hmac('sha256', urldecode(utf8_encode($_str2sign)), base64_decode($key), true)
 );

$signature = urlencode($signature);


?>

<a href="<?php echo STREAM_PATH . $id; ?>/akiba?expire=<?php echo $expire; ?>&callback=downloadWeb&signature=<?php echo $signature; ?>" title="Descarga capitulo" target="_blank"><i class="fa fa-download" aria-hidden="true"></i> Descargar</a> 
</div>
<div class="x-linkPlus"> 
<a href="#" title="#" target="_blank"><i class="fa fa-plus" aria-hidden="true"></i></a>
</div>

</div>

<!-- FIN Descargas -->




<!-- Navegacion-->

<div class="episodioNav">

<div class="lista"><a href="/anime/<?php echo $parentId.'-'.$animeSlug; ?>" title="Lista de episodios"><i class="fa fa-list-alt" aria-hidden="true"></i> Lista</a></div>

<!-- episodio anterior -->
<?php
if( $epiAnterior ){
?>

<div class="anterior"><a href="/episodio/<?php echo $epiAnterior; ?>" title="anterior"><i class="fa fa-step-backward" aria-hidden="true"></i></a></div>

<?php
}else{
?>

<div class="anterior"><a href="#" class="disabled" title="anterior"><i class="fa fa-step-backward" aria-hidden="true"></i></a></div>

<?php
}
?>
<!-- FIN episodio anterior -->




<!-- episodio siguiente -->
<?php
if( $epiSiguiente ){
?>

<div class="siguiente"><a href="/episodio/<?php echo $epiSiguiente; ?>" title="siguiente"><i class="fa fa-step-forward" aria-hidden="true"></i> Siguiente</a></div>

<?php
}else{
?>

<div class="siguiente"><a href="#" class="disabled" title="siguiente"><i class="fa fa-step-forward" aria-hidden="true"></i> Siguiente</a></div>

<?php
}
?>
<!-- FIN episodio siguiente -->






</div>


<!-- Fin Navegacion-->

</div>
<!-- FIN FLEX -->



<!-- ANUNCIOS -->

<div id="ad1">



</div>

<!-- FIN ANUNCIOS -->



</div>
<!-- FIN EpisodioCuerpo -->

















</div>
<!-- FIN Cuerpo de la pagina -->
<div class="clear"></div>





<div class="comentarios" id="comentarios">

<div class="textTop"> <span class="left">Comentarios</span> <span class="right" id="showComentarios"></span> </div>

<div class="comentariosContent">

  <fb:comments href='http://animemovil.net/<?php echo $slug; ?>/' num_posts='15' style='width:100%;' width='100%'></fb:comments>

</div>
</div>





<footer><span>Anime Movil 2014 - <script>document.write(new Date().getFullYear())</script></span></footer>


      
      <script>


      <?php

                            //generar firma para descargar
                            $expire = time()+21600; //expira en 6 horas

                            $_arraysign = array();
                             $_arraysign[] = $id; //id del cap
                             $_arraysign[] = $expire; //expira en 6 horas
                             $_arraysign[] = "playerWeb"; //tipo de callback
                             
                             //IP seguridad
                             if (isset($_SERVER["HTTP_CF_CONNECTING_IP"])) {
                               $_SERVER['REMOTE_ADDR'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
                             }
                            $_arraysign[] = $_SERVER['REMOTE_ADDR']; //Ip de usuario      
                            //FIP IP seguridad

                             $_arraysign[] = SIGNATURE_HASH; //key hash
                             $_str2sign = implode("\n", $_arraysign);
 
                            $signature = base64_encode(
                             hash_hmac('sha256', urldecode(utf8_encode($_str2sign)), base64_decode($key), true)
                             );

                            $signature = urlencode($signature);



      ?>

     episodio_info=<?php echo json_encode( array( "id" => $id, "imgCustom" => $imgCustom, "stream"  => array( "accessPoint" => STREAM_PATH, "callback" => "playerWeb", "servers" => $stream, "expire" => $expire, "signature" => $signature) ), JSON_PRETTY_PRINT); ?>;



      //Funciones necesarias
      function functions_requerid(){
 
      menuServidores();
      showComentarios();
      serversStream();
      menuUsuarioBar();
      buscadorMobile();
      OptionsMenuBar();
      buscadorAjax();

      }


      </script>


<!-- Taboola script -->
<script type="text/javascript">
  window._taboola = window._taboola || [];
  _taboola.push({flush: true});
</script>



<script src="/assets/webApp/jw7/jwplayer.js"></script>
<script async src="/assets/webApp/app.js"></script>


<link href="/assets/webApp/icons/font-awesome.css" rel="stylesheet"/>
</body>
</html>