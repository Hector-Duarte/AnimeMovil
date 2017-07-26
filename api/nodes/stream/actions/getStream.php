<?php


//abrir SQL
$mysqli = new mysqli(HOST, USER, PASSWORD, DATABASE);
if($mysqli->connect_errno){ //Fallo la conexión a SQL
    error("No se ha podido conectar con la base de datos.", 500);
}

    $prep_stmt = "SELECT * FROM stream WHERE id = ? LIMIT 1;";
    $stmt = $mysqli->prepare($prep_stmt);

    $stmt->bind_param('i', $_GET['value']);
    $stmt->execute();

// asociar a su columna
    $meta = $stmt->result_metadata();
    $fields = $meta->fetch_fields();
    foreach($fields as $field) {
        $result[$field->name] = "";
        $resultArray[$field->name] = &$result[$field->name];
    }

    call_user_func_array(array($stmt, 'bind_result'), $resultArray);


    while($stmt->fetch()) {
        $resultObject = new stdClass();

        foreach ($resultArray as $key => $value) {


            $resultObject->$key = $value;

        }

        $rows[] = $resultObject;
    }



//declarar array
$stream=$rows[0];

if($stream->id == $_GET["value"]){

echo json_encode( array('status' => true, 'result' => $stream), JSON_PRETTY_PRINT);
}else{
	error("No hay datos retornados", 400);
}


//cerar SQL
$stmt->close();
$mysqli->close();
