<?php

// $streamInfo->result->NODE //para tomar info del stream

//callbacks permitidos
$callbacks = array( "playerWeb", "downloadWeb");
//comprobar si esta soportado el callback
if(! in_array($callback, $callbacks) ){
  error(501, "El callback no tiene soporte en este nodo");
}





/* STREAM START */
$cookie="__cfduid=d0eae857906ee3f08dea64d0dc13718381490160096; guid=ada3c40d-cf5a-4b5e-8617-3565c88ac2e6; rcid=4; __RequestVerificationToken_Lw__=5X2cseyEwm48lcUpRqEWkkM6LG2Flk1UHMPrZdBW9xOQ4+3dVoE0IJEyPF39EB1KbULMQkAjOdIp2GLsV7IIQO9TudUe0RljucBz/dfCZoOfas8o27co+a0whi6RnfiZ8V6zZQ==; ChomikSession=b8fc0284-0d10-44ca-82af-3d5fba377012; __utmt_b=1; __utmt_ch=1; cookiesAccepted=1; __gfp_64b=ixVwhyhp6DUd4r1ih9bq2QgN2ip6wj38eE3D7UCcRUb.l7; RememberMe=21218390=adfbe6310e6996d84a577f1452b9577a; __utma=215632453.283399759.1490160106.1490160106.1490160106.1; __utmb=215632453.6.10.1490160106; __utmc=215632453; __utmz=215632453.1490160106.1.1.utmcsr=(direct)|utmccn=(direct)|utmcmd=(none); __utmv=215632453.|1=User_ID=21218390=1";

$token="bKsdesGBcmYlBlmC9yMzmb0gC+kgJbbJSddSG0dU3wPvw96axLw2yW9PNJZ/QGuRFVy4uGqFWxjTR7FrkmlqYfkUIeJq5VBQGk67GAIOxEIkt3oc47tvKK4HzBDqf+Xco34AUA==";




$postdata = http_build_query(
    array(
        '__RequestVerificationToken' => $token,
        'fileId' => $streamInfo->result->copy

    )
);




$opts = array(
  'http'=>array(
    'method'=>"POST",
    'header'=>
"User-Agent: Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/46.0.2490.80 Safari/537.36\r\n".
"Content-Type: application/x-www-form-urlencoded\r\n".
"X-Requested-With: XMLHttpRequest\r\n".
"Cookie: ".$cookie."\r\n",
'content' => $postdata
   )
);

$context = stream_context_create($opts);


//new
$downloadContent=json_decode(file_get_contents("http://chomikuj.pl/action/License/DownloadContext", false, $context));



$downloadContent=$downloadContent->Content;

$SerializedUserSelection=explode('"SerializedUserSelection" name="SerializedUserSelection" type="hidden" value="',$downloadContent);
$SerializedUserSelection=explode('"',$SerializedUserSelection);
$SerializedUserSelection=$SerializedUserSelection[0];




$SerializedOrgFile=explode('"SerializedOrgFile" name="SerializedOrgFile" type="hidden" value="',$downloadContent);
$SerializedOrgFile=explode('"',$SerializedOrgFile);
$SerializedOrgFile=$SerializedOrgFile[0];


$postdata = http_build_query(
    array(
        '__RequestVerificationToken' => $token,
        'fileId' => $streamInfo->result->copy,
        'SerializedOrgFile' => $SerializedOrgFile,
        'SerializedUserSelection' =>$SerializedUserSelection

    )
);

$opts = array(
  'http'=>array(
    'method'=>"POST",
    'header'=>
"User-Agent: Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/46.0.2490.80 Safari/537.36\r\n".
"Content-Type: application/x-www-form-urlencoded\r\n".
"X-Requested-With: XMLHttpRequest\r\n".
"Cookie: ".$cookie."\r\n",
'content' => $postdata
   )
);


$context = stream_context_create($opts);


//new



$downloadContent=json_decode(file_get_contents("http://chomikuj.pl/action/License/DownloadWarningAccept", false, $context));


if($downloadContent->redirectUrl){

//todo ok, retornar enlace
$streamReturn=$downloadContent->redirectUrl;

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
                                                               "abouttext" => "chomikuj 30/05/2017",
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