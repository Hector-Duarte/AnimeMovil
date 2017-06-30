<?php

if($_SERVER["REQUEST_METHOD"] == "POST"){
	$json = array('mensaje' => $_POST["mensaje"], 'expire' => time() + ( $_POST["TimeType"] * $_POST["time"] ) );
	$json = json_encode($json, JSON_PRETTY_PRINT);
	file_put_contents("/var/www/html/static/mensaje.json", $json);
}


?>


<form class="formulario" method="post" id="mensaje">
	<input type="submit" value="Actualizar"/><br><br><br><br>
	<input type="number" name="time" value="30" style="width:auto;"/><br>
	<select name="TimeType">
	      <option value="60">Minutos</option>
	      <option value="3600">Horas</option>
	      <option value="86400">Dias</option>
    </select>
</form>

<textarea style="width:80%;margin:20px auto;display:block;outline:0;border-radius:3px;padding:10px;border:0;height:200px;" form="mensaje" name="mensaje"><?php 

$json = file_get_contents("/var/www/html/static/mensaje.json");

if($json){
	$json = json_decode($json);
	echo $json->mensaje;
}


?></textarea>

