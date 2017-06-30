<?php

$newImageId=uniqid();
$outinput->newImageId=$newImageId;

$outinput->nodes=["portada","completo","diminuto","episodio"];


$outinput->request->method="PUT";
$outinput->request->auth=true;
$outinput->request->endpoint="/api/img/{id}/{node}";
