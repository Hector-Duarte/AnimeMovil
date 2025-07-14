<?php

$method=$_SERVER["REQUEST_METHOD"];






if($method=="POST"){
//iniciando sesison auth

$error=false;

//include_once en raiz
require_once("../vars_info.php");

//funciones para admin panel
require_once("functiones.php"); 

//include en raiz



//start session
     startSession();


//finalizando sesion POST
}else{

              //borrar cookies 
              setcookie("session_user_id", false, time() - 3600, "/");
              setcookie("session_user_name", false, time() - 3600, "/");
              setcookie("session_id", false, time() - 3600, "/");
              setcookie("session_user_level", false, time() - 3600, "/");
              setcookie("session_expire", false, time() - 3600, "/");
              setcookie("session_hash", false, time() - 3600, "/");


}



?><!DOCTYPE html>
<html lang="es">
<head>
	<title>Iniciar Sesión</title>
	<link rel="stylesheet" type="text/css" href="/AnimeMovil/assets/webApp/panel.css">
        <link rel="icon" href="/AnimeMovil/assets/webApp/favicon.png" type="image/png"/>
        <link rel="shortcut icon" href="/AnimeMovil/assets/webApp/favicon.png" type="image/png"/>
        <meta content='width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1' name='viewport'/>
        <meta charset="utf-8"/>
</head>
<body>


<!-- Formulario para iniciar sesion -->
<div class="usuarioForm">
 <div class="logoLogin"><a href="/AnimeMovil/" title="Pagina principal"><img src="/AnimeMovil/assets/webApp/logo.png"/></a></div>
   <form method="post">
   <input type="text" name="usuario" placeholder="Usuario" required/>
   <input type="password" name="password" placeholder="Contraseña" required/>
   <input type="submit" value="Iniciar Sesión"/>
   <form/>

<?php
//error al hacer login
if($method=="POST" AND $error){
?>
<div class="errorLogin"><span><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> Error al iniciar sesión</span></div>
<?php
}
?>

<!--- Crear cuenta -->
<div class="registrarUsuario"><a href="/registrar" title="Registrar usuario">¿No tienes cuenta? Registrar.</a></div>


</div>

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
</body>
</html>
