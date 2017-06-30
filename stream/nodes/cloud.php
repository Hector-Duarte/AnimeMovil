<?php

// $streamInfo->result->NODE //para tomar info del stream

//callbacks permitidos
$callbacks = array( "playerWeb", "downloadWeb");
//comprobar si esta soportado el callback
if(! in_array($callback, $callbacks) ){
	error(501, "El callback no tiene soporte en este nodo");
}





/* STREAM START */

$nuevaurl="https://www.amazon.com/gp/drive/share/download?mgh=1&s=".$streamInfo->result->cloud."&sid=000-0000000-0000000";

$ch2 = curl_init(); 
curl_setopt($ch2, CURLOPT_URL, $nuevaurl); 
curl_setopt($ch2, CURLOPT_HEADER, true); 
curl_setopt($ch2, CURLOPT_FOLLOWLOCATION, false); 
curl_setopt($ch2, CURLOPT_RETURNTRANSFER, TRUE); 
curl_setopt($ch2, CURLOPT_USERAGENT,'googlebot');
curl_setopt($ch2, CURLOPT_SSL_VERIFYPEER, FALSE);
$tmp2 = curl_exec($ch2);
$urlownload = explode("ocation: ",$tmp2);			
$urlownload = explode("\r",$urlownload[1]);
$urlownload = explode("\n",$urlownload[0]);

//salida del enlace
$streamReturn = $urlownload[0];

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
                                                               "abouttext" => "amazonv1 30/05/2017",
                                                               "aboutlink" => "/",
                                                               "primary" => "flash",
                                                               "height" => "100%",
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

        header("Location: ". $streamReturn); //redirrecionar a la descarga
        exit(); //finalizar script
   		break;
   }






}
//FIN retornar enlace del stream