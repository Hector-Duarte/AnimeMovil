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

/* >>>>> OBJECT JSON



*/


//es forzado que se mande un pass para verificar y uno para actualizar
if( !is_numeric($input->update->pass) || !is_numeric($input->pass) ){
  error("Requerido pass actual del nodo y un nuevo pass", 400);
}

//preparar valores a actualizar
$input_pass = $input->update->pass;
$query_set = " pass = $input_pass "; //se estable el nuevo valor de pass

                               //actualizar subRequerido
                               if( is_numeric($input->update->subRequerido) ){
                                 $input_subRequerido = $input->update->subRequerido;
                                 $query_set .= " , subRequerido = $input_subRequerido ";
                               }

                               //actualizar subtitleId
                               if( is_numeric($input->update->subtitleId) ){
                                 $input_subtitleId = $input->update->subtitleId;
                                 $query_set .= " , subtitleId = $input_subtitleId ";
                               }

                               //actualizar streamInfo
                               if($input->update->streamInfo){
                                 $input_streamInfo = $input->update->streamInfo;
                                 $query_set .= " , streamInfo = '$input_streamInfo' "; //tiene que ser base64
                               }

                               //actualizar idEpisodio
                               if( is_numeric($input->update->idEpisodio) ){
                                 $input_idEpisodio = $input->update->idEpisodio;
                                 $query_set .= " , idEpisodio = $input_idEpisodio ";
                               }

                               //actualizar idCrunchy
                               if( is_numeric($input->update->idCrunchy) ){
                                 $input_idCrunchy = $input->update->idCrunchy;
                                 $query_set .= " , idCrunchy = $input_idCrunchy ";
                               }


echo $query_set;exit();


//actualizar el elemento
$prep_stmt = "UPDATE crunchyroll SET $query_set  WHERE id = ? AND pass = ? LIMIT 1;";
$stmt = $mysqli->prepare($prep_stmt);

$stmt->bind_param('ii', $_GET['value'], $input->pass); //se entrega el id que se actualizara y el pass que tiene que ser el estado actual
$stmt->execute();


respuesta_ok( array( "message" => "El elemento se ha actualizado" ) , 204);
