<?php

//validar session

//entrada de datos
$input = json_decode(file_get_contents('php://input'));

$username = $input->user;
$password = $input->password;

createSession($username, $password);
