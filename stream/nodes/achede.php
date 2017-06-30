<?php

// $streamInfo->result->NODE //para tomar info del stream

//callbacks permitidos
$callbacks = array( "playerWeb", "downloadWeb");
//comprobar si esta soportado el callback
if(! in_array($callback, $callbacks) ){
	error(501, "El callback no tiene soporte en este nodo");
}





/* STREAM START */

$amazon=file_get_contents("https://www.amazon.com/drive/v1/shares/".$streamInfo->result->achede."?resourceVersion=V2&ContentType=JSON&asset=ALL");

$amazon=json_decode($amazon);

$amazon=file_get_contents("https://www.amazon.com/drive/v1/nodes/".$amazon->nodeInfo->id."/children?resourceVersion=V2&tempLink=true&shareId=".$streamInfo->result->achede);


$amazon=json_decode($amazon);


if($amazon->data[0]->tempLink){
//todo ok retornar enlace
  $streamReturn = $amazon->data[0]->tempLink;
}




/* STREAM END */






//retornar enlace del stream
if($streamReturn){


   switch ($callback) {
   	case 'playerWeb':
   		header("Content-Type: application/json"); //para recibir en el playerweb

   		echo json_encode(
   			 array('status' => true, 'result' => array(
   			 	          'kind' => 'jwplayer', 'setup' => array( 
                                                               "file" => $streamReturn,
                                                               "type" => "video/mp4",
                                                               "skin" => "bekle",
                                                               "width" => "100%",
                                                               "abouttext" => "amazonv2 30/05/2017",
                                                               "aboutlink" => "/",
                                                               "height" => "100%",
                                                               "primary" => "html5",
                                                               "autostart" => true,
                                                                       "sharing" => array(
                                                                                   "code" => "<iframe src='https://animemovil.com/share/".$id."' width='320' height='260' frameborder='0' scrolling='auto'></iframe>"
                                                                                         )
                                                          )  
   			 	                                         )
   			    	)
   			, JSON_PRETTY_PRINT); //fin de json output
        
        exit(); //finalizar script
   		break;
   	
   	case 'downloadWeb':
   		header("Content-Type: video/mp4"); //para enviar a la descarga

        header("Location: ". $streamReturn ."?download=true"); //redirrecionar a la descarga
        exit(); //finalizar script
   		break;
   }






}
//FIN retornar enlace del stream