<?php

require_once("C:\\xampp\\htdocs\\AnimeMovil\\vars_info.php");

$cachePath = CACHE_PATH . "anime-" .$_GET['id']. "-info.json";



if( file_exists( $cachePath ) ){

//existe cache
header("x-cache: HIT");
$cache=json_decode( file_get_contents($cachePath) );


//datos del anime
$id = $cache->anime->id;
$status = $cache->anime->status;
$title = $cache->title;
$slug = $cache->anime->slug;
$simulcasts = $cache->anime->simulcasts;
$sinopsis = $cache->anime->sinopsis;
$emision = $cache->anime->emision;
$nextEpi = $cache->anime->nextEpi;
$collection = $cache->anime->collection;
$message = $cache->anime->message;

//generos
$generos = $cache->generos;

//episodios del anime (childrens)
$episodios = $cache->childrens;

//collecciones
$animesRelacionados = $cache->collection;



//FIN existe cache
}else{
//no existe cache
header("x-cache: MISS");


$cache=new stdClass();
$cache->anime = new stdClass(); // Inicializar el objeto anime

//conectar a base de datos
$mysqli = new mysqli(HOST, USER, PASSWORD, DATABASE);



    $prep_stmt = "SELECT id, status, title, slug, simulcasts, sinopsis, emision, nextEpi, collection, message FROM animes WHERE id = ? AND status = 1  LIMIT 1;";
    $stmt = $mysqli->prepare($prep_stmt);

    $stmt->bind_param('i', $_GET["id"]);
    $stmt->execute();
    $stmt->store_result();

    //asignar valores recibidos
    $stmt->bind_result($id, $status, $title, $slug, $simulcasts, $sinopsis, $emision, $nextEpi, $collection, $message);
    $stmt->fetch();


$cache->anime->id = $id;
$cache->anime->status = $status;
$cache->title = $title;
$cache->anime->slug = $slug;
$cache->anime->simulcasts = $simulcasts;
$cache->anime->sinopsis = $sinopsis;
$cache->anime->emision = $emision;
$cache->anime->nextEpi = $nextEpi;
$cache->anime->collection = $collection;
$cache->anime->message = $message;



   //error 404
   if(!$id){ 
     echo "error 404";
     exit(); 

      //cerrar sql
      $stmt->close();
      $mysqli->close();
}


    //generos
    $prep_stmt = "SELECT idGenero FROM generos WHERE idAnime = ? LIMIT 22;";
    $stmt = $mysqli->prepare($prep_stmt);

    $stmt->bind_param('i', $id);
    $stmt->execute();
    $stmt->store_result();

    //asignar valores recibidos
    $stmt->bind_result($idGenero);

    $generos=array();
    while( $stmt->fetch() ){
    $generos[]=$idGenero;
    }


//cache
$cache->generos=$generos;



    //FIN generos




    //Episodios del anime

    $prep_stmt = "SELECT id, title, slug, numEpi, imgCustom FROM episodios WHERE parentId = ? AND status = 1 ORDER BY numEpi desc LIMIT 1000;";
    $stmt = $mysqli->prepare($prep_stmt);

    $stmt->bind_param('i', $id);
    $stmt->execute();
    $stmt->store_result();

    //asignar valores recibidos
    $stmt->bind_result($epiId, $epiTitle, $epiSlug, $epiNumEpi, $capImgCustom);

    $episodios=array();
    $epiTotales=0;


    while( $stmt->fetch() ){

$episodios[$epiTotales] = new stdClass(); // Inicializar objeto
$episodios[$epiTotales]->id=$epiId;
$episodios[$epiTotales]->title=$epiTitle;
$episodios[$epiTotales]->slug=$epiSlug;
$episodios[$epiTotales]->num=$epiNumEpi;
$episodios[$epiTotales]->imgCustom=$capImgCustom;


$epiTotales=$epiTotales+1;

    }

//cache
$cache->childrens=$episodios;


    //Episodios del anime






    //relacionados del anime
    if($collection != 0){

    $prep_stmt = "SELECT id, title, slug FROM animes WHERE collection = ? AND id != ? AND status = 1 LIMIT 15;";
    $stmt = $mysqli->prepare($prep_stmt);

    $stmt->bind_param('ii', $collection, $id);
    $stmt->execute();
    $stmt->store_result();

    //asignar valores recibidos
    $stmt->bind_result($relaId, $relaTitle, $relaSlug);

    $animesRelacionados=array();
    $animesRelacionadosTotales=0;


    while( $stmt->fetch() ){

$animesRelacionados[$animesRelacionadosTotales] = new stdClass(); // Inicializar objeto
$animesRelacionados[$animesRelacionadosTotales]->id=$relaId;
$animesRelacionados[$animesRelacionadosTotales]->title=$relaTitle;
$animesRelacionados[$animesRelacionadosTotales]->slug=$relaSlug;


$animesRelacionadosTotales=$animesRelacionadosTotales+1;
    }


//cache
$cache->collection=$animesRelacionados;

    }

    //relacionados del anime




    //cerrar sql
    $stmt->close();
    $mysqli->close();

//FIN conectar a base de datos


//guardar cache
file_put_contents($cachePath, json_encode($cache));


}//fin cache no existe





    //comprobar slug
    if($_GET["slug"] != $slug){ header("Location: /anime/$id-$slug");exit(); }










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
        <meta property="fb:app_id" content="356185744523413"/>
        <meta content="AnimeMovil" property="og:site_name"/>
        <meta name="referrer" content="never"/>
        <meta name="keywords" content="Anime, anime online, anime sub español, anime en celular"/>
        <meta content="<?php echo htmlentities($sinopsis); ?>" name="description"/>
        <meta content='Anime Móvil' name='Author'/>
        <meta property="og:title" content="<?php echo $title; ?>"/>
        <meta property="og:type" content="video.episode"/>
        <meta content='/AnimeMovil/assets/media/anime-<?php echo $id; ?>_grande.jpg' property='og:image'/>
        <meta content='general' name='rating'/>
        <meta name="robots" content="noindex, nofollow"/>
        <link rel="stylesheet" type="text/css" href="/AnimeMovil/assets/webApp/app.css"/>
        <link rel="stylesheet" type="text/css" href="/AnimeMovil/assets/webApp/mejoras.css"/>
        <link rel="shortcut icon" href="/AnimeMovil/assets/webApp/favicon.png" type="image/png"/>
        <link href='/AnimeMovil/assets/media/anime-<?php echo $id; ?>_grande.jpg' rel='image_src'/>









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

<div class="logo"><a href="/AnimeMovil/" title="Pagina principal"><img src="/AnimeMovil/assets/webApp/logo.png"/></a></div>





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

<!-- AnimeCuerpo -->
<div class="AnimeCuerpo">



<!-- ANIME INFO -->
<div class="animeInfo">



<div class="animePortada">
<img src="/AnimeMovil/assets/media/anime-<?php echo $id; ?>_portada.jpg"/>

<div class="x-emisionEstado x-<?php if($simulcasts=="0"){ echo "false"; }else{ echo "true"; } ?>">
<?php if($simulcasts=="0"){ echo "Finalizado"; }else{ echo "En emisión"; } ?>
</div>

<?php
if( $sesion_status == true ){
?>
<div class="x-favorito x-true" method="GET" id="botonFavorito">
Favorito
</div>

<script>

//conexion a api
function favoritos(){
xmlhttp=new XMLHttpRequest();
xmlhttp.onreadystatechange=function(){
if(xmlhttp.readyState==4){
if(xmlhttp.status>=200&&xmlhttp.status<300){

//recibir valores
apiResponse=JSON.parse(xmlhttp.responseText);


       //Iniciar evento con el metodo GET
       if( botonFavorito.getAttribute("method") == "GET"){
             //comprobar si esta disponible el anime en los favoritos
             if( apiResponse.result.available == true ){
                   botonFavorito.classList.remove("x-true");
                   botonFavorito.classList.add("x-false");
                   botonFavorito.setAttribute("method", "DELETE");
                   
                  }else{
                       botonFavorito.setAttribute("method", "POST");
                       
                       }


        }

       //Iniciar evento con el metodo POST
       if( botonFavorito.getAttribute("method") == "POST"){
             //comprobar si esta disponible el anime en los favoritos
             if( apiResponse.result.available == true ){
                   botonFavorito.classList.remove("x-true");
                   botonFavorito.classList.add("x-false");
                   botonFavorito.setAttribute("method", "DELETE");
                   
                  }else{
                       botonFavorito.setAttribute("method", "POST");
                       
                       }


        }

       //Iniciar evento con el metodo DELETE
       if( botonFavorito.getAttribute("method") == "DELETE"){
             //comprobar si esta disponible el anime en los favoritos
             if( apiResponse.result.available == false ){
                   botonFavorito.classList.remove("x-false");
                   botonFavorito.classList.add("x-true");
                   botonFavorito.setAttribute("method", "POST");
                   
                  }else{
                       botonFavorito.setAttribute("method", "DELETE");
                       
                       }


        }


}else{
//error

alert("Error al obtener enlaces");


}}}


xmlhttp.withCredentials=true;

xmlhttp.open( botonFavorito.getAttribute("method") ,"/api/favoritos/<?php echo $id; ?>",true);




xmlhttp.send();

}
botonFavorito = document.getElementById("botonFavorito");
botonFavorito.addEventListener("click", favoritos);

favoritos();

</script>

<?php
}
?>


</div>


<div class="animeData">

<div class="x-text"><span><i class="fa fa-star" aria-hidden="true"></i> Info</span></div>


<div class="x-title"><?php echo $title; ?></div>

<div class="x-generos">
<?php

//generos matriz
$generos_data=[
"Harem",
"Acción",
"Comedia",
"Colegial",
"Mecha",
"Cocina",
"Misterio",
"Deportes",
"Fantasía",
"Drama",
"Romance",
"Ecchi",
"Horror",
"Shounen",
"Aventura",
"Historico",
"Magia",
"Música",
"Juegos",
"Yuri",
"Yaoi",
"Sobrenatural"
];

//fin generos matriz



for($i=0;$generos[$i] || $generos[$i]=="0";$i++){

echo '<a href="/anime?genero='.$generos[$i].'" title="#">'.$generos_data[$generos[$i]].'</a> ';

}


?>


</div>




<div class="x-emision"><?php echo $emision; ?></div>

<div class="x-sinopsis">
<?php echo $sinopsis; ?>
</div>


</div>


</div>
<!-- FIN ANIME INFO -->




<!-- mensaje del administrador -->
<?php
if($message){

echo '<div class="epiMenssge"><span>' . $message . '</span></div>';

}
?>
<!-- FIN mensaje del administrador -->


<!-- ADS 2 -->
<div class="ads2">

              <div id="taboola-below-article-thumbnails"></div>
              <script type="text/javascript">
              window._taboola = window._taboola || [];
              _taboola.push({
                mode: 'thumbnails-a',
                container: 'taboola-below-article-thumbnails',
                placement: 'Below Article Thumbnails',
                target_type: 'mix'
              });
              </script>
</div>




<!-- content flex episodios y animes relacionados -->
<div class="animeCapitulosContent">




<!-- episodios indexs-->
<div class="episodiosIndex">

<div class="x-upText"><span><i class="fa fa-youtube-play" aria-hidden="true"></i> Episodios</span></div>


<?php
if($nextEpi){
?>
<div class="nextEpi"><span><i class="fa fa-newspaper-o"></i> Próximo Episódio: <?php echo $nextEpi; ?></span></div>
<?php
}
?>


<?php
//si no hay episodios
if( count($episodios) == 0){
echo '<div class="notEpisodes"><span>¡No hay episodios disponibles!</span></div>';
}
?>



<ul class="list" id="showEpisodes">


<?php
//imprimir episodios
$num=0;
while($episodios[$num]){


//imagenes personalizadas
if( $episodios[$num]->imgCustom ){
$episodios[$num]->imgCustom = '/AnimeMovil/assets/media/episodio-' . $episodios[$num]->id . '_pequena.jpg';
}else{
$episodios[$num]->imgCustom = '/AnimeMovil/assets/media/anime-' . $id . '_grande-pequena.jpg';
}

//imprimir
echo '<li><a href="/AnimeMovil/episodio/' . $episodios[$num]->id .'-' . $episodios[$num]->slug .'" title="' . $episodios[$num]->title .'"> <img src="' . $episodios[$num]->imgCustom .'" alt="' . $episodios[$num]->title .'"/> <span>Episodio #' . $episodios[$num]->num .' -   ' . $episodios[$num]->title .'</span> </a> </li>';


$num=$num+1;
}

unset($episodios);
?>


</ul>


</div>
<!-- fin episodios indexs-->


<!-- animer relacionados -->
<div class="animeRelacionados">

<div class="x-upText"><span><i class="fa fa-clone" aria-hidden="true"></i> Anime Relacionados</span></div>

<?php
if($collection == 0 ){
?>

<div class="notCollection"><span><i class="fa fa-chain-broken" aria-hidden="true"></i> No hay relacionados</span></div>

<?php
}else{
?>

<ul>

<?php
$num=0;
while($num < count($animesRelacionados) ){

echo '<li><a href="/anime/' . $animesRelacionados[$num]->id . '-' . $animesRelacionados[$num]->slug . '" title="' . $animesRelacionados[$num]->title . '"> <img src="/AnimeMovil/assets/media/anime-' . $animesRelacionados[$num]->id . '_pequena.jpg"/> <span>' . $animesRelacionados[$num]->title . '</span> </a></li>';
$num=$num+1;
}

?>

</ul>

<?php
}
?>

</div>
<!-- animer relacionados -->



<!-- FIN content flex episodios y animes relacionados -->
</div>




</div>
<!-- FIN AnimeCuerpo -->

















</div>
<!-- FIN Cuerpo de la pagina -->
<div class="clear"></div>





<div class="comentarios" id="comentarios">

<div class="textTop"> <span class="left">Comentarios</span> <span class="right" id="showComentarios"></span> </div>

<div class="comentariosContent">

  <fb:comments href='http://animemovil.net/' num_posts='5' style='width:100%;' width='100%'></fb:comments>

</div>
</div>





<footer><span>Anime Movil 2014 - <script>document.write(new Date().getFullYear())</script></span></footer>


      
      <script>
      //Funciones necesarias
      function functions_requerid(){
 
       showComentarios();
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



<script async src="/AnimeMovil/assets/webApp/app.js"></script>


<link href="/AnimeMovil/assets/webApp/icons/font-awesome.css" rel="stylesheet"/>
</body>
</html>
