<?php

// $streamInfo->result->NODE //para tomar info del stream

//callbacks permitidos
$callbacks = array( "playerWeb", "downloadWeb");
//comprobar si esta soportado el callback
if(! in_array($callback, $callbacks) ){
	error(501, "El callback no tiene soporte en este nodo");
}





/* STREAM START */






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
                                                               "primary" => "flash",
                                                               "skin" => "bekle",
                                                               "width" => "100%",
                                                               "abouttext" => "amazonv1 30/05/2017",
                                                               "aboutlink" => "/",
                                                               "preload" => "none",
                                                               "repeat" => false,
                                                               "height" => "100%",
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

        header("Location: ". $streamReturn); //redirrecionar a la descarga
        exit(); //finalizar script
   		break;
   }






}
//FIN retornar enlace del stream