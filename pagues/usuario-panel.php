<?php

//include_once en raiz
require_once("/var/www/html/vars_info.php");


//funciones para admin panel
require_once("functiones.php"); 

//validar sesion
$session = validateSession(1);


?>

<!DOCTYPE html>
<html lang="es">
<head>
        <title>Panel</title>
        <link rel="shortcut icon" href="/assets/webApp/favicon.png" type="image/png"/>
        <meta content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1" name="viewport"/>
        <meta charset="utf-8"/>
	<link rel="stylesheet" href="/assets/webApp/panel.css">
	<link href="/assets/webApp/icons/font-awesome.css" rel="stylesheet"/>
        <meta name="robots" content="noindex, nofollow"/>
        <script src="/assets/webApp/panel.js"></script>
</head>

<body>






<header class="cabecera">

<div class="logo"><a href="/" title="Pagina principal"><img src="/assets/webApp/logo.png"/></a></div>

<div class="buscador">
  <form method="post" action="/buscar">
     <input type="text" placeholder="Buscar..." required/>
     <button type="submit"><i class="fa fa-search" aria-hidden="true"></i></button>
  </form>
</div>


<div class="headerSociales">
<a href="#" title="#" class="googleplus" target="_blank"><i class="fa fa-google-plus" aria-hidden="true"></i></a>
<a href="#" title="#" class="facebook" target="_blank"><i class="fa fa-facebook" aria-hidden="true"></i></a>
<a href="#" title="#" class="twitter" target="_blank"><i class="fa fa-twitter" aria-hidden="true"></i></a>
</div>


<div class="menuLinks">
<ul>
<li><a href="#" title="#"><i class="fa fa-youtube-play" aria-hidden="true"></i> Animes</a></li>
<li><a href="#" title="#"><i class="fa fa-calendar-check-o" aria-hidden="true"></i> Emisión</a></li>
</ul>
</div>




<div class="opcionesUsuario">
<i class="fa fa-user-o" aria-hidden="true"></i>

<ul class="menuUsuario">
<li><a href="#" title="#">xxxxxxxxxxxxxxxx</a></li>
<li><a href="#" title="#">xxxx</a></li>
<li><a href="#" title="#">xxxx</a></li>
<li><a href="#" title="#">xxxx</a></li>
<li><a href="#" title="#">xxxx</a></li>
</ul>

</div>

</header>



<div class="panel">


<?php
include("usuario-panel/".$_GET["pague"].".php");
?>


</div>







</body>

</html>