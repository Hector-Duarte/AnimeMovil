<?php

// $streamInfo->result->NODE //para tomar info del stream

//callbacks permitidos
$callbacks = array( "playerWeb", "downloadWeb");
//comprobar si esta soportado el callback
if(! in_array($callback, $callbacks) ){
	error(501, "El callback no tiene soporte en este nodo");
}





/* STREAM START */


$cookie="guid=9fef1535-e827-453e-a33a-9bf09517c15f; rcid=9; __RequestVerificationToken_Lw__=7aE0BmMfvRZfowUW1ZuaiYj25SatZRLOZlsmXMebW9tYavVPIEZrJ3Ppne1BoHU15CxgQFxwnFaSwn1z502SckfZXEHOgGxQIxu+HCQ4hJzLSUp/Rozhvn2zVkmtUl9W+TvA5g==; ChomikSession=19376a79-c1b0-4c81-9009-c881464714cf; RememberMe=4329097=adfbe6310e6996d84a577f1452b9577a; mpDB=1; __kdtv=t%3D1457278650000%3Bi%3D07dd64d4ebea43a02e5165686a7e6438147da23f; _kdt=%7B%22t%22%3A1457278650000%2C%22i%22%3A%2207dd64d4ebea43a02e5165686a7e6438147da23f%22%7D";



$token="/HMS1OppB2UGuzIYoBZokiHrvljs9pV8hwBk1jbByqlo4Ctx8fhLXxivgZIbzzHqMF6ZEvW60pzTUu785Rcmyru9Z22d4N4VSq2CaVzZjvGQx54BrtzEtZ93oaDPSiDPngjQYg==";




$postdata = http_build_query(
    array(
        '__RequestVerificationToken' => $token,
        'fileId' => $streamInfo->result->minh

    )
);




$opts = array(
  'http'=>array(
    'method'=>"POST",
    'header'=>
"User-Agent: Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/46.0.2490.80 Safari/537.36\r\n".
"Content-Type: application/x-www-form-urlencoded\r\n".
"X-Requested-With: XMLHttpRequest\r\n".
"Cookie: ".$cookie,
'content' => $postdata
   )
);

$context = stream_context_create($opts);


$downloadContext=json_decode(file_get_contents("http://minhateca.com.br/action/License/Download", false, $context));


if($downloadContext->redirectUrl){
//todo ok retornar enlace
  $streamReturn=$downloadContext->redirectUrl;
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
                                                               "abouttext" => "minhateca 30/05/2017",
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