<?php

// $streamInfo->result->NODE //para tomar info del stream

//callbacks permitidos
$callbacks = array( "playerWeb", "downloadWeb");
//comprobar si esta soportado el callback
if(! in_array($callback, $callbacks) ){
	error(501, "El callback no tiene soporte en este nodo");
}


                          //comprobar si existe cache
                          if ( file_exists("cache/nora-".$id.".json") ) {
                            $nora_cache = file_get_contents("cache/nora-".$id.".json");
                            $nora_cache = json_decode($nora_cache);
                                          //comprobar si no ha expirado
                                          if( $nora_cache->expire > time() ) {
                                            $nora_cache_status = true;
                                          }else{
                                            $nora_cache_status = false;
                                          }
            
                          }else{
                                          //no existe cache
                                          $nora_cache_status = false;
                          }


/* STREAM START */
if (!$nora_cache_status) {
//cache no existe

$data=file_get_contents("http://ec2-34-208-56-100.us-west-2.compute.amazonaws.com/apis/api_privada.php?id=https://drive.google.com/file/d/".$streamInfo->result->nora."/view");


$busqueda=strpos($data, "videoplayback");

if($busqueda){
  //hay video
                                //todo ok, regresar fuernes y guardar en cache
                                $data=json_decode($data);
                                $streamReturn= $data->result->data->sources;;
                                file_put_contents("cache/nora-".$id.".json", json_encode( array(
                                                                 "expire" => time() + 10800, "data" => $streamReturn
                                                                                              )
                                                                                      )
                                                  );

}else{
//no hay video disponible
  $streamReturn=false;
}


//fin de no existe cache
}else{
  //cache existe
  $streamReturn = $nora_cache->data;
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
                                                               "sources" => $streamReturn,
                                                               "skin" => "bekle",
                                                               "width" => "100%",
                                                               "abouttext" => "google drive 30/05/2017",
                                                               "aboutlink" => "/",
                                                               "primary" => "html5",
                                                               "autostart" => true,
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

        header("Location: ". $streamReturn[0]->file . "&title=video-".$id ); //redirrecionar a la descarga
        exit(); //finalizar script
      break;
   }






}
//FIN retornar enlace del stream