<?php

// $streamInfo->result->NODE //para tomar info del stream

//callbacks permitidos
$callbacks = array( "playerWeb", "downloadWeb");
//comprobar si esta soportado el callback
if(! in_array($callback, $callbacks) ){
	error(501, "El callback no tiene soporte en este nodo");
}





/* STREAM START */

                          //comprobar si existe cache
                          if ( file_exists("cache/rin-".$id.".json") ) {
                            $rin_cache = file_get_contents("cache/rin-".$id.".json");
                            $rin_cache = json_decode($rin_cache);
                                          //comprobar si no ha expirado
                                          if( $rin_cache->expire > time() ) {
                                            $rin_cache_status = true;
                                          }else{
                                            $rin_cache_status = false;
                                          }
            
                          }else{
                                          //no existe cache
                                          $rin_cache_status = false;
                          }

if (!$rin_cache_status) {
//cache no existe

$pag=file_get_contents("https://goo.gl/photos/".$streamInfo->result->rin);
$pag=explode("video.googleusercontent.com/",$pag);
$pag=explode('https://',$pag[1]);
$pag= explode('"',$pag[1]);

$num=0;

$jw=array();

if(strpos($pag[0],"googleusercontent.com")){
//se detecto video
$pag= "https://".$pag[0];


//720p
$google=get_headers($pag."=m22");
if(strpos($google[0],"302")){

$jw[$num]->file="https://redirector.googlevideo.com".explode("googlevideo.com",explode("Location: ",$google[1])[1])[1];
$jw[$num]->type="video/mp4";
$jw[$num]->default="true";
$jw[$num]->label="HD 720p";

$num=$num+1;
}
//720p



//360p
$google=get_headers($pag."=m18");
if(strpos($google[0],"302")){

$jw[$num]->file="https://redirector.googlevideo.com".explode("googlevideo.com",explode("Location: ",$google[1])[1])[1];
$jw[$num]->type="video/mp4";
$jw[$num]->label="SD 360p";

$num=$num+1;
}else{ 
  //no de detecto redireccion en la calidad mas baja
  $streamReturn=false;
 }
//360p

                                //todo ok, regresar fuernes y guardar en cache
                                $streamReturn= $jw;
                                file_put_contents("cache/rin-".$id.".json", json_encode( array(
                                                                 "expire" => time() + 10800, "data" => $streamReturn
                                                                                              )
                                                                                      )
                                                  );

}else{
//no se detecto googleusercontent.com, por lo que no hay video que mostrar
  $streamReturn=false;

}

//fin de cache no existe
}else{
  //cache existe
  $streamReturn = $rin_cache->data;
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
                                                               "abouttext" => "google photos 30/05/2017",
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