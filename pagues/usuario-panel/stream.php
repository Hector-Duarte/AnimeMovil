<?php


//requerido ser admin
adminValidate();


?>

<div class="returnPanelHome">

  <a href="/panel"><i class="fa fa-reply" aria-hidden="true"></i> Panel</a>

</div>



<!-- Insertar datos enviados en POST-->
<?php
if($_SERVER["REQUEST_METHOD"] == "POST"){


//obtener servidores enviados
$servers=json_decode($_POST["SERVIDORES"]);
$servers_count=count($servers);
$num=0;

        //conectar a base de datos
        $mysqli = new mysqli(HOST, USER, PASSWORD, DATABASE);

 while($num < $servers_count){

    $prep_stmt = "UPDATE stream SET ".$servers[$num]." = ? WHERE id = ? LIMIT 1;";

    $stmt = $mysqli->prepare($prep_stmt);

    $stmt->bind_param('si', $_POST[$servers[$num]], $_GET['value']);

    $stmt->execute();

     $num=$num+1;
     }


//borrar cache stream
unlink("/var/www/html/assets/cache/episodio-" .$_GET['value']. "-stream.json");



}
?>
<!-- FIN Insertar datos enviados en POST-->



<?php
//tomar id para buscar stream
if( is_numeric($_GET["value"]) ){
?>




<!-- FORMULARIO -->
<div class="formulario">
<div class="x-content">

<form method="POST" autocomplete="off">

<input type="submit" value="Actualizar"/>
<div class="clear"></div>

<br><br>


<?php
//mostrar datos del stream solicitado


        //conectar a base de datos
        $mysqli = new mysqli(HOST, USER, PASSWORD, DATABASE);


    $prep_stmt = "SELECT * FROM stream WHERE id = ? LIMIT 1;";
    $stmt = $mysqli->prepare($prep_stmt);

    $stmt->bind_param('i', $_GET["value"]);
    $stmt->execute();



// asociar a su columna
    $meta = $stmt->result_metadata();
    $fields = $meta->fetch_fields();
    foreach($fields as $field) {
        $result[$field->name] = "";
        $resultArray[$field->name] = &$result[$field->name];
    }

    call_user_func_array(array($stmt, 'bind_result'), $resultArray);
        $rows = array();$num_count=0;
    while($stmt->fetch()) {


        foreach ($resultArray as $key => $value) {


//no retornar id, path y file
if($key != "id"){



       echo '<i class="fa fa-star-half-o" aria-hidden="true"></i> '.$key.':<br><input maxlength="500" name="'.$key.'" value="'.$value.'" type="text"/><br><br>';
        $rows[$num_count] = $key;$num_count=$num_count+1;
}

        }


    }


echo '<input name="SERVIDORES" value=\''.json_encode($rows).'\' type="hidden"/>';


?>








<div class="BorrarBoton">
<a href="/panel/stream/<?php echo $_GET["value"]; ?>?cache=false"><i class="fa fa-trash-o" aria-hidden="true"></i> Purgar Cache</a>
<?php

if( $_GET["cache"] == "false" ){

//stream
unlink("/var/www/html/assets/cache/episodio-" .$_GET['value']. "-stream.json");

}

?>
</div>

</form></div></div>



<?php
}//fin de ID
else{

echo '<div class="errorLogin"><span><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> No se ha encontrado stream</span></div>';

}

?>




