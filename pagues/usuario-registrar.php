<?php


$method=$_SERVER["REQUEST_METHOD"];



if($method=="POST"){
//iniciando registro

$error=false;

//include_once en raiz

require_once("/var/www/html/vars_info.php");

//include en raiz




if (isset($_POST['usuario'], $_POST['correo'], $_POST['password'], $_POST['passwordConfirmar'])){
//iseet

//entrada de datos
    $usuario = filter_input(INPUT_POST, 'usuario', FILTER_SANITIZE_STRING);
    $correo = filter_input(INPUT_POST, 'correo', FILTER_SANITIZE_EMAIL);
    $correo = filter_var($correo, FILTER_VALIDATE_EMAIL);
    $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);
    $passwordConfirmar = filter_input(INPUT_POST, 'passwordConfirmar', FILTER_SANITIZE_STRING);

//comprobar correo
if($correo AND $password==$passwordConfirmar){

//crear SALT unico
$random_salt = hash('sha512', uniqid(mt_rand(1, mt_getrandmax()), true));

// Crea una contraseña con SALT. 
$password = hash('sha512', $password . $random_salt);


//mandar a base de datos
$mysqli = new mysqli(HOST, USER, PASSWORD, DATABASE);

    $prep_stmt = "INSERT INTO usuarios (user, mail, password, salt, level) VALUES (?,?,?,?,1);";
    $stmt = $mysqli->prepare($prep_stmt);
    $stmt->bind_param('ssss', $usuario, $correo, $password, $random_salt);


 // Ejecuta la consulta preparada.
            if (! $stmt->execute()) {
                $error="Error al crear nuevo usuario (posiblemente usuario o correo ya existente).";
           /* cerrar conexión */
            $mysqli->close();
 }else{

/* cerrar conexión */
$mysqli->close();


//enviar al login
header("Location: /entrar");exit(); 
}



//FIN de comprobar correo
}else{$error="Comprueba el correo y la constraseña.";}



//FIN isset
}


//finalizando registro POST
}



?><!DOCTYPE html>
<html lang="es">
<head>
	<title>Registrar</title>
	<link rel="stylesheet" type="text/css" href="/assets/webApp/panel.css">
        <link rel="icon" href="/assets/webApp/favicon.png" type="image/png"/>
        <link rel="shortcut icon" href="/assets/webApp/favicon.png" type="image/png"/>
        <meta content='width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1' name='viewport'/>
        <meta charset="utf-8"/>
</head>
<body>


<!-- Formulario para iniciar sesion -->
<div class="usuarioForm">
 <div class="logoLogin"><a href="/" title="Pagina principal"><img src="/assets/webApp/logo.png"/></a></div>
   <form method="post">
   <input type="text" name="usuario" placeholder="Usuario" required/>
   <input type="text" name="correo" placeholder="Correo" required/>
   <input type="text" name="password" placeholder="Contraseña" required/>
   <input type="text" name="passwordConfirmar" placeholder="Confirmar contraseña" required/>
   <input type="submit" value="Registrar"/>
   <form/>

<?php
//error al hacer login
if($method=="POST" AND $error){
?>
<div class="errorLogin"><span><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> <?php echo $error;?></span></div>
<?php
}
?>

<!--- Crear cuenta -->
<div class="registrarUsuario"><a href="/entrar" title="Registrar usuario">¿Ya tienes cuenta? Entrar.</a></div>


</div>


<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
</body>
</html>