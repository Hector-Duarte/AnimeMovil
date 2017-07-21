<?php

//validar session

//entrada de datos
$input = json_decode(file_get_contents('php://input'));

//asignar valores
$username = $input->user;
$password = $input->password;


if( isset($username) and isset($password) ){ //validar si son aceptables los valores
createSession($username, $password);
}
