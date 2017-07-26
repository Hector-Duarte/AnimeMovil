<?php



//abrir SQL
$mysqli = new mysqli(HOST, USER, PASSWORD, DATABASE);
if($mysqli->connect_errno){ //Fallo la conexión a SQL
    error("No se ha podido conectar con la base de datos.", 500);
}

    $prep_stmt = "SELECT a.id, b.path, b.file from episodios as a, stream as b WHERE a.id = b.id and a.status = 1 and (a.imgCustom is null or a.imgCustom = '') order by id desc LIMIT 1;";
    $stmt = $mysqli->prepare($prep_stmt);

    $stmt->execute();
    $stmt->store_result();

    //asignar valores recibidos
    $stmt->bind_result($id, $path, $file);

if( $stmt->fetch() ){
	//imprimir encontrado
	respuesta_ok( array( "id" => $id, "path" => $path, "file" => $file ) ,200);
}else{
	//no hay pendientes
	error("ya no hay mas episodios", 200);
}



//cerar SQL
$stmt->close();
$mysqli->close();
