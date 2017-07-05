<?php

//actualizar elementos

//abrir SQL
$mysqli = new mysqli(HOST, USER, PASSWORD, DATABASE);

//varificar que el ID enviado es numerico
if( !is_numeric($_GET['value']) ){
  error("El ID proporcionado no es valido.", 400);
}

//entrada de datos
$input = json_decode(file_get_contents('php://input'));



//preparar valores a actualizar
$query_set = " pass = $input->pass ";

echo $query_set;exit();


//actualizar el elemento
$prep_stmt = "UPDATE crunchyroll SET  WHERE id = ? LIMIT 1;";
$stmt = $mysqli->prepare($prep_stmt);

$stmt->bind_param('i', $idCrunchy);
$stmt->execute();
$stmt->store_result();

//asignar valores recibidos
$stmt->bind_result($idCrunchy_sql);
$stmt->fetch();
