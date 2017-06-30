
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
            header("Location: /entrar");exit();
          return false;
           }


}

     $sesion_status = validateSession();




?><!DOCTYPE html>
<html lang="es">
<head>
  <title>Mis animes</title>
        <link rel="shortcut icon" href="/assets/webApp/favicon.png" type="image/png"/>
        <meta content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1" name="viewport"/>
        <meta charset="utf-8"/>
        <meta content="AnimeMovil" property="og:site_name"/>
        <meta name="robots" content="noindex, nofollow"/>
        <link rel="stylesheet" type="text/css" href="/assets/webApp/app.css"/>
</head>
<body>


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
                                               <li><a href="/misAnimes" title="Animes favoritos"><i class="fa fa-star" aria-hidden="true"></i> Favoritos</a></li>
                               <li><a href="/historial" title="Historial"><i class="fa fa-clock-o" aria-hidden="true"></i> Historial</a></li>
                               <li><a href="/perfil" title="Editar perfil"><i class="fa fa-user" aria-hidden="true"></i> Perfil</a></li>
                               <li><a href="/logout" title="Cerrar sesión"><i class="fa fa-sign-out" aria-hidden="true"></i> Cerrar sesión</a></li>
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
<div class="info"><span><i class="fa fa-info-circle" aria-hidden="true"></i> Maximo de 100 unidades por cuenta.</span></div>


<div class="buscadorFavotiros">
  <form method="get" action="/misAnimes" autocomplete="off">
    <input type="text" name="q" placeholder="Busca entre tus favoritos..." required/>
    <input type="submit" value="Buscar"/>
  </form>
</div>


<!-- episodios indexs-->
<div class="episodiosIndex">


<ul class="hover">






<?php
// imprimir caps

//consulta para cadena 
function sql_like(){
    if( $_GET["q"] AND strlen( $_GET["q"] ) <= 70  ){
    $text = str_replace(' ', '-', $_GET["q"]);
    $text = preg_replace("/[^A-Za-z0-9\-]/", "", $text) ;
    $text = " b.title LIKE '%". $text ."%' ";
    $text = str_replace('-', '%', $text);
    return $text;
    }else{
          return 1;
          }
}


//conectar a base de datos
$mysqli = new mysqli(HOST, USER, PASSWORD, DATABASE);


    $prep_stmt = "SELECT b.id, b.title, b.slug FROM favoritos as a, animes as b WHERE a.id = ? AND b.status = 1 AND a.nodeId = b.id AND ". sql_like() ." ORDER BY b.title asc LIMIT 100;";
    $stmt = $mysqli->prepare($prep_stmt);
    $stmt->bind_param('i', $_COOKIE["session_user_id"]);
    $stmt->execute();
    $stmt->store_result();

    //asignar valores recibidos
    $stmt->bind_result($id, $title, $slug);

//capturar si no tiene favoritos el usuario
    $favoritosUser=false;

while( $stmt->fetch() ){

//tiene favoritos el usuario
      $favoritosUser=true;

//imprimir
echo '<li id="fav'. $id .'">
  <a href="/anime/'. $id .'-'. $slug .'" title="'. $title .'">
   <img src="/assets/media/anime-'. $id .'_grande.jpg"/>
     <span>'. $title .'</span>
  </a>
  <div class="borrarFavorito" node="'. $id .'" nodeId="fav'. $id .'"><span><i class="fa fa-trash" aria-hidden="true"></i></span></div>
</li>';


}



    //cerrar SQL
    $stmt->close();
    $mysqli->close();


?>





</ul>

<div class="clear"></div>

</div><br><br>
<!-- fin episodios indexs-->


<?php
if(!$favoritosUser){
?>
<div class="error"><span><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> No tienes animes marcados</span></div>
<?php
}
?>



</div>
<!-- FINcuerpoHomeLeft -->

















</div>
<!-- FIN Cuerpo de la pagina -->
<div class="clear"></div>







<footer><span>Anime Movil 2014 - <script>document.write(new Date().getFullYear())</script></span></footer>


      
      <script>
      //Funciones necesarias
      function functions_requerid(){
      menuUsuarioBar();
      buscadorMobile();
      OptionsMenuBar();
      buscadorAjax();
      favoritosBorrar();

      }


      </script>

<script async src="/assets/webApp/app.js"></script>
<link href="/assets/webApp/icons/font-awesome.css" rel="stylesheet"/>
<script async src="//connect.facebook.net/es_LA/sdk.js#xfbml=1&version=v2.3&appId=356185744523413"></script>
</body>
</html>