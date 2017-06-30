<?php

if($_SERVER["REQUEST_METHOD"]=="POST"){

//conexion a bd
$db= mysqli_connect('localhost', 'root', '7445018937', 'animemovil');
mysqli_set_charset($db,'utf8');


$user_name=mysqli_real_escape_string($db,$_POST["userName"]);
$user_password=md5(mysqli_real_escape_string($db,$_POST["password"]));

$query="SELECT id FROM admins WHERE userName='".$user_name."' AND password='".$user_password."'  LIMIT 1;";


//ejecutar consulta
$result=mysqli_query($db,$query);
$sql_object=mysqli_fetch_object($result);

if($sql_object->id){

//asignar cookies
setcookie("adminId", $sql_object->id, time()+2592000, "/");
setcookie("adminPass", $user_password, time()+2592000, "/");


header("Location: /webAdmin/");
exit();
}



//cerrar SQL
mysqli_close($db);
}


?>
<!DOCTYPE HTML>
<html lang="es">
<head>
<title>Iniciar sesión</title> 
<meta content='width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1' name='viewport'/>
<meta content="noindex, nofollow" name="robots"/>
<meta charset="utf-8"/>
<link rel="icon" href="/assets/webApp/favicon.png" type="image/png"/>
<link rel="shortcut icon" href="/assets/webApp/favicon.png" type="image/png"/>
</head>
<body>

<div class="logo"><a href="/"><img src="/assets/webApp/logo.png"/></a></div>


<div class="form">

<div class="textUp"><span>Administración</span></div>

<form method="post" autocomplete="off">
Usuario:<br> <input type="text" name="userName" required/>
Contraseña:<br> <input type="password" name="password" required/>

<input type="submit" value="Autenticar"/>
</form>


</div>








<style>
/*RESET.CSS*/
html, body, div, span, applet, object, iframe,
h1, h2, h3, h4, h5, h6, p, blockquote, pre,
a, abbr, acronym, address, big, cite, code,
del, dfn, em, img, ins, kbd, q, s, samp,
small, strike, strong, sub, sup, tt, var,
b, u, i, center,
dl, dt, dd, ol, ul, li,
fieldset, form, label, legend,
table, caption, tbody, tfoot, thead, tr, th, td,
article, aside, canvas, details, embed, 
figure, figcaption, footer, header, hgroup, 
menu, nav, output, ruby, section, summary,
time, mark, audio, video {
	margin: 0;
	padding: 0;
	border: 0;
	font-size: 100%;
	font: inherit;
	vertical-align: baseline;
}
/* HTML5 display-role reset for older browsers */
article, aside, details, figcaption, figure, 
footer, header, hgroup, menu, nav, section {
	display: block;
}
body {
	line-height: 1;
}
ol, ul {
	list-style: none;
}
blockquote, q {
	quotes: none;
}
blockquote:before, blockquote:after,
q:before, q:after {
	content: '';
	content: none;
}
table {
	border-collapse: collapse;
	border-spacing: 0;
}
/* FIN RESET.CSS*/


/*FONTS*/
@import url('//fonts.googleapis.com/css?family=Roboto');


/*KEY FRAMES*/
@keyframes down{
from {top:-10px;opacity:.6;}
to {top:0;opacity:1;}
}
/*KEY FRAMES*/


/*TAGS*/
body{
     animation:down 1s;
     background:#f1f4f5;
     font-family: 'Roboto', sans-serif;
     position:relative;
}


.logo{
text-align:center;
margin:50px auto 30px auto;
}
.logo img{
width:180px;
max-width:80%;
}

.form{
width:600px;
max-width:90%;
margin:0 auto 100px auto;
text-align:center;
background:#FFF;
font-size:18px;
border-radius:5px;
}

.form form{
padding:20px 0;
}
.form form input{
display:inline-block;
width:90%;
margin:10px 0;
height:35px;
}

.form form input[type*="text"],.form form input[type*="password"]{
font-size:19px;
border:0;
background:#F5F5F5;
border-radius:2px;
outline:0;
padding-left:5px;
}

.form form input[type*="submit"]{
background:#04AEF7;
color:#FFF;
border:0;
outline:0;
font-size:16px;
height:40px;
}

.textUp{
height:50px;
line-height:50px;
background:#04AEF7;
color:#FFF;
font-size:18px;
}
</style>
</body>
</html>