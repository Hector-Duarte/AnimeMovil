<?php

//variables basicas
require_once("/var/www/html/vars_info.php");









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




?><!DOCTYPE html>
<html lang="es">
<head>
	<title>Anime Móvil</title>
        <link rel="shortcut icon" href="/assets/webApp/favicon.png" type="image/png"/>
        <meta content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1" name="viewport"/>
        <meta charset="utf-8"/>
        <meta content="AnimeMovil" property="og:site_name"/>
        <meta name="keywords" content="Anime, anime online, anime sub español, anime en celular"/>
        <meta name="robots" content="noindex, nofollow"/>
        <link rel="stylesheet" type="text/css" href="/assets/webApp/app.css"/>




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

<!-- cuerpoHomeLeft -->
<div class="cuerpoHomeLeft">

<!-- INFO -->
<?php 

//imprimir mensaje del admin
$json = file_get_contents("/var/www/html/static/mensaje.json");

if($json){
  $json = json_decode($json);
     if( $json->expire - time() > 0 ){
      echo '<div class="info"><span><i class="fa fa-info-circle" aria-hidden="true"></i> '. $json->mensaje. '</span></div>';
     }
}


?>

<!-- texto info-->
<div class="infoText"><span><i class="fa fa-bookmark"></i>  Emisión diaria</span></div>


<!-- episodios indexs-->
<div class="episodiosIndex homeIndex">


<ul class="hover">




<?php
// imprimir caps

//conectar a base de datos
$mysqli = new mysqli(HOST, USER, PASSWORD, DATABASE);


    $prep_stmt = "SELECT id, title, slug, numEpi, imgCustom, parentId FROM episodios WHERE simulcasts = 1 AND status = 1 ORDER BY id desc LIMIT 28;";
    $stmt = $mysqli->prepare($prep_stmt);

    $stmt->execute();
    $stmt->store_result();

    //asignar valores recibidos
    $stmt->bind_result($id, $title, $slug, $numEpi, $imgCustom, $parentId);

while( $stmt->fetch() ){

//img
if( $imgCustom == 1){
$imgUrl = "/assets/media/episodio-".$id."_grande.jpg";
}else{
$imgUrl = "/assets/media/anime-".$parentId."_grande.jpg";
}

//imprimir
echo '<li><a href="/episodio/' . $id .'-' . $slug .'" title="' . $title .'"> <img src="' . $imgUrl .'" alt="#"/> <span>' . $title .'</span> </a> </li>';

}



    //cerrar SQL
    $stmt->close();
    $mysqli->close();


?>





</ul>


<div class="clear"></div>

</div>
<!-- fin episodios indexs-->


<div class="ads1">
  <!-- Taboola script -->
  <div id="taboola-right-rail-bulk"></div>
  <script type="text/javascript">
  window._taboola = window._taboola || [];
  _taboola.push({
    mode: 'right-rail-bulk1',
    container: 'taboola-right-rail-bulk',
    placement: 'Right Rail-Bulk',
    target_type: 'mix'
  });
  </script>
  <div class="clear"></div>
</div>





</div>
<!-- FINcuerpoHomeLeft -->

















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


<script async src="/assets/webApp/app.js"></script>
<link href="/assets/webApp/icons/font-awesome.css" rel="stylesheet"/>
</body>
</html>