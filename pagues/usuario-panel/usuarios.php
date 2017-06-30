<?php


//requerido ser admin
adminValidate();


?>



<?php

if( $_GET["value"]  AND $_SERVER["REQUEST_METHOD"] == "POST" ){


$mysqli = new mysqli(HOST, USER, PASSWORD, DATABASE);

    $prep_stmt = "UPDATE usuarios SET user = ?, mail = ?, level = ? WHERE id = ? LIMIT 1;";
    $stmt = $mysqli->prepare($prep_stmt);

    $stmt->bind_param('ssii', $_POST["user"], $_POST["mail"], $_POST["level"], $_GET["value"]);

    $stmt->execute();


}

?>




<div class="returnPanelHome">

  <a href="/panel"><i class="fa fa-reply" aria-hidden="true"></i> Panel</a>

</div>


<!-- INDEX de los usuarios -->
<?php
if(!$_GET["value"]){

//mostrar episodios


//conectar a base de datos
$mysqli = new mysqli(HOST, USER, PASSWORD, DATABASE);


function offset(){if($_GET["offset"]){ return 20*$_GET["offset"]; }else{ return 0; } }

function buscar(){

if($_GET["q"]){
return " user LIKE  '%" . $_GET["q"] . "%'  OR  " . " mail LIKE  '%" . $_GET["q"] . "%' ";
}else{
return 1;
}

}


    $prep_stmt = "SELECT id, user, mail FROM usuarios WHERE " . buscar() . " LIMIT 20 OFFSET ". offset() ." ;";
    $stmt = $mysqli->prepare($prep_stmt);


    $stmt->execute();
    $stmt->store_result();

    //asignar valores recibidos
    $stmt->bind_result($id, $user, $mail);
    


?>





<div class="buscadorPanel">
<form method="GET" action="/panel/usuarios">
<input type="text" name="q" placeholder="Buscar..." value="<?php   if($_GET["q"]){ echo $_GET["q"]; }    ?>"/>


<input type="submit" value="Buscar"/>

</form>
</div>





<table>
  <tr class="hover">
    <th>User</th>
    <th>Corre</th>
    <th>Editar</th> 
  </tr>



<?php

            //imprimir episodios
            while( $stmt->fetch() ){
            ?>



  <tr>
    <td><?php echo $user; ?></td>
    <td><?php echo $mail; ?></td>
    <td><a href="/panel/usuarios/<?php echo $id; ?>">Editar</a> </td>
  </tr>

            <?php
            }


?>




            </table>










<div class="offset">

<div class="return"><a href="/panel/usuarios?offset=<?php if(!$_GET["offset"]){ echo 0;}else{ echo $_GET["offset"]-1; }   if($_GET["q"]){ echo "&q=".$_GET["q"];}      if($_GET["parentId"]){ echo "&parentId=".$_GET["parentId"];}     ?>">Anterior</a></div>

<div class="next"><a href="/panel/usuarios?offset=<?php if(!$_GET["offset"]){ echo 1;}else{ echo $_GET["offset"]+1; }    if($_GET["q"]){ echo "&q=".$_GET["q"];}    if($_GET["parentId"]){ echo "&parentId=".$_GET["parentId"];}   ?>">Proximo</a></div>


</div>
<!-- FIN INDEX -->
<?php
}else if( $_GET["value"] AND $_GET["borrar"] == "true"  AND $_SERVER["REQUEST_METHOD"] == "GET" ){ //
?>

<!-- BORRAR USUARIO -->

<?php

$mysqli = new mysqli(HOST, USER, PASSWORD, DATABASE);

    //borrar de tabla episodios
    $prep_stmt = "DELETE FROM usuarios WHERE id = ? LIMIT 1;";
    $stmt = $mysqli->prepare($prep_stmt);
    $stmt->bind_param('i', $_GET["value"]);
    $stmt->execute();

    $stmt->close();
    $mysqli->close();

//redirreccionar a los views de usuarios
echo '<script>window.location="/panel/usuarios/";</script>';
exit();


?>


<!-- FIN BORRAR USUARIO -->


<?php
}else if( $_GET["value"]  ){
?>

<!-- Imprimri datos del usuario -->


<?php

$mysqli = new mysqli(HOST, USER, PASSWORD, DATABASE);

    $prep_stmt = "SELECT user, level, mail FROM usuarios WHERE id = ? LIMIT 1;";
    $stmt = $mysqli->prepare($prep_stmt);

    $stmt->bind_param('i', $_GET["value"]);

    $stmt->execute();
    $stmt->store_result();


    //asignar valores recibidos
    $stmt->bind_result($user, $level, $mail);

?>


<?php
//revisar si existe usuario
if($stmt->fetch()){
?>

<!-- FORMULARIO -->
<div class="formulario">
<div class="x-content">

<form method="POST">

<input type="submit" value="Actualizar"/>
<div class="clear"></div>

<br><br>
User: <br><input value="<?php echo $user; ?>" name="user" type="text" required/>
<br><br>

Correo: <br><input value="<?php echo $mail; ?>" name="mail" type="text" required/>
<br><br>

Nivel del usuario
<br>
<select name="level">
  <option value="1" <?php if($level == 1){ echo "selected";} ?>>Usuario Normal</option>
  <option value="0" <?php if($level == 0){ echo "selected";} ?>>Administrador</option>
</select>



</form>

<div class="BorrarBoton">
<a href="/panel/usuarios/<?php echo $_GET["value"]; ?>?borrar=true"><i class="fa fa-trash-o" aria-hidden="true"></i> Borrar</a>
</div>


</div></div>
<!-- FIN formulario -->
<?php
}else{
echo '<div class="errorLogin"><span><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> El Usuario no existe</span></div>';
}
?>


<!-- FIN Imprimri datos del usuario -->

<?php
}
?>



<!-- CONTENIDO -->



<!-- FIN CONTENIDO -->


</div>