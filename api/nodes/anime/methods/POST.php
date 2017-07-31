<?php

//validar session
checkSession('API', true); //'API' es el tipo de callback y el true es que tiene que ser admin.


$datos  = array(
'original' => $_FILES['imgPortada']['tmp_name'],
'contenedor' => 'animes',
'blob' => 'blob.jpg',
'resolucion' => array(90,90),
'calidad' => 100
);
miniaturas($datos);
