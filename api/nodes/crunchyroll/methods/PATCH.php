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

//es forzado que se mande un pass para verificar y uno para actualizar
if( !is_numeric($input->update->pass) || !is_numeric($input->pass) ){
  error("Requerido pass actual del nodo y un nuevo pass", 400);
}

//preparar valores a actualizar
$query_set = " pass = $input->update->pass "; //se estable el nuevo valor de pass

                               //actualizar subRequerido
                               if( is_numeric($input->update->subRequerido) ){
                                 $query_set += " , subRequerido = $input->update->subRequerido ";
                               }

                               //actualizar subtitleId
                               if( is_numeric($input->update->subtitleId) ){
                                 $query_set += " , subtitleId = $input->update->subtitleId ";
                               }

                               //actualizar streamInfo
                               if($input->update->streamInfo){
                                 $query_set += " , streamInfo = '$input->update->streamInfo' "; //tiene que ser base64
                               }

                               //actualizar idEpisodio
                               if( is_numeric($input->update->idEpisodio) ){
                                 $query_set += " , idEpisodio = '$input->update->idEpisodio' ";
                               }

                               //actualizar idCrunchy
                               if( is_numeric($input->update->idCrunchy) ){
                                 $query_set += " , idCrunchy = $input->update->idCrunchy ";
                               }


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
