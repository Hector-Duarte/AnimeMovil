<?php

//validar session

checkSession('API', true); //'API' es el tipo de callback y el false es que no es necesario que sea admin

      if(SESSION_STATUS){ //si la session es valida.
        respuesta_ok( array(
        "session_is_valid" => true
                           ), 200);

      }else{//la session no es valida
        respuesta_ok( array(
        "session_is_valid" => false
                           ), 200);
      }
