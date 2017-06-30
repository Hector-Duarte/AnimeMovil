<?php

//comprobar cookies
if($_COOKIE["userId"] AND $_COOKIE["userMd5"]){

//conexion a bd
$db= mysqli_connect('localhost', $sql_user, $sql_password, 'animemovil');
mysqli_set_charset($db,'utf8');

//vars
$userId=mysqli_real_escape_string($db,$_COOKIE["userId"]);
$userMd5=mysqli_real_escape_string($db,$_COOKIE["userMd5"]);

      //query a ejecutar
      $query="SELECT * FROM usuarios WHERE id='".$userId."' AND password='".$userMd5."' LIMIT 1;";

      //ejecutar consulta
      $result =mysqli_query($db,$query);

      if( $sql_object=mysqli_fetch_object($result) ){
      $outinput=$sql_object;
      }else{
http_response_code(400);
$outinput->Message="Sesion de usuario no valida";
//expirar cookies
setcookie("userId", false, time() - 3600, "/");
setcookie("userMd5", false, time() - 3600, "/");
 } 


//cerrar SQL
mysqli_close($db);

//fin de comprobar cookies
}else{
http_response_code(400);
$outinput->Message="Sesion de usuario no valida";
//expirar cookies
setcookie("userId", false, time() - 3600, "/");
setcookie("userMd5", false, time() - 3600, "/");
}