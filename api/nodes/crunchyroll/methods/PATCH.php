<?php

//actualizar elementos

//abrir SQL
$mysqli = new mysqli(HOST, USER, PASSWORD, DATABASE);

//varificar que el ID enviado es numerico
if( !is_numeric($_GET['value']) ){
  error("El ID proporcionado no es valido.", 400);
}

//consultar si existe la ID enviada
$prep_stmt = "UPDATE crunchyroll SET  WHERE idCrunchy = ? LIMIT 1;";
$stmt = $mysqli->prepare($prep_stmt);

$stmt->bind_param('i', $idCrunchy);
$stmt->execute();
$stmt->store_result();

//asignar valores recibidos
$stmt->bind_result($idCrunchy_sql);
$stmt->fetch();
