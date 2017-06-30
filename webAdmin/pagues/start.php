<?php
header("Cache-Control: max-age=0, no-cache");

//autenticar
if(!$_COOKIE["adminId"] AND !$_COOKIE["adminPass"]){

//Mandar a login
header("Location: /webAdmin/login.php");exit();
}else{

//conexion a bd
$db= mysqli_connect('localhost', 'root', '7445018937', 'animemovil');
mysqli_set_charset($db,'utf8');

//parsear el include
$include=mysqli_real_escape_string($db,$_GET["include"]);

//obtener cookies del admin
$user_id=mysqli_real_escape_string($db,$_COOKIE["adminId"]);
$user_password=mysqli_real_escape_string($db,$_COOKIE["adminPass"]);

//consulta
$query="SELECT id FROM admins WHERE password='".$user_password."'  LIMIT 1;";

//ejecutar consulta
$result=mysqli_query($db,$query);

//obtener object respuesta
$sql_object=mysqli_fetch_object($result);



//User admin errornio
if($sql_object->id!=$user_id){

//Mandar a login
header("Location: /webAdmin/login.php");exit();

}

//fin de IF primario para autenticar
}

?>
<!DOCTYPE HTML>
<html lang="es">
<head>
<title>Admin <?php echo $include;?></title>

<!-- Metas generales -->

<meta charset="utf-8"/>
<meta name="robots" content="noindex"/>
<meta content='width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1' name='viewport'/>
<link rel="icon" href="/assets/webApp/favicon.png" type="image/png"/>
<link rel="shortcut icon" href="/assets/webApp/favicon.png" type="image/png"/>
<link rel="stylesheet" href="/assets/webApp/reset.css"/>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css"/>
<style>
@import url('https://fonts.googleapis.com/css?family=Sansita');

body{
background:#FFF;
font-family: 'Sansita', sans-serif;
}
</style>









<?php include("nodes/".$include.".php");?>
</body>
</html>