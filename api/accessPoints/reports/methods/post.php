<?php


//conexion a bd
$db= mysqli_connect('localhost', $sql_user, $sql_password, 'animemovil');
mysqli_set_charset($db,'utf8');




if(!$resource){

//entrada de datos
$input = json_decode(file_get_contents('php://input'));


//escapear
$input_id=mysqli_real_escape_string($db,$input->id);
$input_title=mysqli_real_escape_string($db,$input->title);
$input_text=mysqli_real_escape_string($db,$input->text);


//comprobar que se reciben todos los datos
if($input->id AND $input->title AND $input->text){


 //query a ejecutar
      $query="INSERT INTO reports (id, status, title, text) VALUES ('".$input_id."', 'PENDING', '".$input_title."', '".$input_text."');";

      //ejecutar consulta
      if(!($result=mysqli_query($db,$query))){
      http_response_code(400);
      $outinput->Message="Error al insertar nueva data";
      }else{ http_response_code(201); $outinput->nodeId=$input_id;$outinput->status="PENDING"; }



}else{
      //intento de hackeo
      http_response_code(400);
      $outinput->Message="Parametros requeridos (id,title,text)";
}






}else{
      //intento de hackeo
      http_response_code(400);
      $outinput->Message="Solicitud invalida, no especifique recurso - reports/{id}";

}





//cerrar SQL
mysqli_close($db);