<?php

//conexion a bd
$db= mysqli_connect('localhost', $sql_user, $sql_password, 'animemovil');
mysqli_set_charset($db,'utf8');


//escapear
$input = json_decode(file_get_contents('php://input'));
$user_name=mysqli_real_escape_string($db,$input->user);
$user_password=md5(mysqli_real_escape_string($db,$input->password));



//condicion para evaluar si se registra
if($input->user AND $input->password){
//revision de datos

//query
$query="INSERT INTO usuarios (userName,password) VALUES ('".$user_name."','".$user_password."');";


//ejecutar consulta
if(!($result=mysqli_query($db,$query))){
//registro erroneo
http_response_code(400);
$outinput->Message="Error al crear usuario";
}else{ 
//registro exitoso
http_response_code(201); 
$outinput->userId=mysqli_insert_id($db);

//asignar cookies
setcookie("userId", $outinput->userId, time()+2592000, "/");
setcookie("userMd5", $user_password, time()+2592000, "/");


}



//fin revision de datos
}else{ http_response_code(400);$outinput->Message="valores no validos para su uso"; } 


//cerrar SQL
mysqli_close($db);