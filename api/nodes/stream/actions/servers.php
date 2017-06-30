<?php

//abrir SQL
$mysqli = new mysqli(HOST, USER, PASSWORD, DATABASE);

//node
$node = $_GET['value'];

$setId=$_GET['id'];

switch ($_SERVER["REQUEST_METHOD"]) {
    case 'GET':
//consultar IDs pendientes de subir
              if(!is_numeric($setId)){
               $prep_stmt = "SELECT a.id, a.path, a.file FROM stream as a, episodios as b WHERE ($node IS NULL OR $node = '') AND b.id = a.id AND b.status = 1 ORDER BY RAND() LIMIT 1;";
              }else{
               $prep_stmt = "SELECT a.id, a.path, a.file FROM stream as a, episodios as b WHERE b.id = $setId AND b.id = a.id ORDER BY RAND() LIMIT 1;";
              }

               $stmt = $mysqli->prepare($prep_stmt);

               $stmt->execute();
               $stmt->store_result();

               //asignar valores recibidos
               $stmt->bind_result($id, $path, $file);
               $stmt->fetch();


                             if($id){
	                             //hay ID pendiente
                                respuesta_ok( array('id' => $id, 'path' => $path, 'file' => $file, 'upload_name' => "EP$file $path [$id][$node].mp4" ), 200);
                             }else{
	                             //no hay IDs pendientes
	                             error("No hay episodios pendientes.", 200);
                             }


        break;



        case 'POST':
//para agregar o actualizar IDs

        //entrada de datos
        $input = json_decode(file_get_contents('php://input'));
               $prep_stmt = "UPDATE stream SET $node = ? WHERE id = ? LIMIT 1;";
               $stmt = $mysqli->prepare($prep_stmt);
               $stmt->bind_param('si', $input->value, $input->id);
            
                             if( $stmt->execute() ){
                                 //hay ID pendiente
                                respuesta_ok( array('update' => true, 'value' => $input->value ), 200);
                             }else{
                                 //no hay IDs pendientes
                                 error("No se ha completo el proceso", 400);
                             }

        break;

    default:
             error(404, 'Metodo no soportado ');
        break;
}


//cerar SQL
$stmt->close();
$mysqli->close();