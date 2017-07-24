<?php

//validar session

checkSession('API', false); //'API' es el tipo de callback y el false es que no es necesario que sea admin

      if(SESSION_STATUS){ //si la session es valida.
$user_id, $user_level, $ip, $token, $expire

        respuesta_ok( array(
        "ip" => $ip,
        "expire" => $expire
                           ), 200);

      }
