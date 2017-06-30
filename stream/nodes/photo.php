<?php

// $streamInfo->result->NODE //para tomar info del stream

//callbacks permitidos
$callbacks = array( "playerWeb", "downloadWeb");
//comprobar si esta soportado el callback
if(! in_array($callback, $callbacks) ){
  error(501, "El callback no tiene soporte en este nodo");
}



                          //comprobar si existe cache
                          if ( file_exists("cache/photo-".$id.".json") ) {
                            $photo_cache = file_get_contents("cache/photo-".$id.".json");
                            $photo_cache = json_decode($photo_cache);
                                          //comprobar si no ha expirado
                                          if( $photo_cache->expire > time() ) {
                                            $photo_cache_status = true;
                                          }else{
                                            $photo_cache_status = false;
                                          }
            
                          }else{
                                          //no existe cache
                                          $photo_cache_status = false;
                          }



/* STREAM START */
if (!$photo_cache_status) {
//cache no existe


$pag=file_get_contents("https://goo.gl/photos/".$streamInfo->result->photo);


$pag=explode("video-downloads.googleusercontent.com/",$pag);

$pag=explode('"',$pag[1]);

//comprobar si hay video
if($pag[0]){
//hay video
$streamReturn="https://video.googleusercontent.com/".$pag[0];


                                //todo ok, regresar fuernes y guardar en cache
                                file_put_contents("cache/photo-".$id.".json", json_encode( array(
                                                                 "expire" => time() + 600, "data" => $streamReturn
                                                                                              )
                                                                                      )
                                                  );


}else{
  //no hay video
  $streamReturn=false;
}


//fin de no existe cache
}else{
  //cache existe
  $streamReturn = $photo_cache->data;
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
                                                               "abouttext" => "google photos 30/05/2017",
                                                               "aboutlink" => "/",
                                                               "autostart" => true,
                                                               "height" => "100%",
                                                               "primary" => "flash",
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
        //finalizar script
      break;
   }






}
//FIN retornar enlace del stream