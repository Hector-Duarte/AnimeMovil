<?php

 //este nodo es para automatico, se utilizaran los nodos segun el callback y la disponibilidad de estos e prioridad


   switch($callback){

   	case "playerWeb":
    //cuando se ve online

                     //llamar servidor solo si se usa flash
   	                 if($streamInfo->result->cloud AND $flash){
   	                 	include_once('cloud.php');
   	                 }


                     //llamar servidor amazon
   	                 if($streamInfo->result->achede){
   	                 	include_once('achede.php');
   	                 }

                     //llamar servidor google drive
   	                 if($streamInfo->result->nora){
   	                 	include_once('nora.php');
   	                 }

                     //llamar servidor google photos tipo templink googlevideo
   	                 if($streamInfo->result->rin){
   	                 	include_once('rin.php');
   	                 }


                     //llamar servidor google photos tipo templink usercontent y que use flash
   	                 if($streamInfo->result->photo AND $flash){
   	                 	include_once('photo.php');
   	                 }


                     //llamar servidor chomikuj
   	                 if($streamInfo->result->copy){
   	                 	include_once('copy.php');
   	                 }


                     //llamar servidor minhateca
   	                 if($streamInfo->result->minh){
   	                 	include_once('minh.php');
   	                 }


                                                         //ningun nodo finalizo la solicitud, por lo que se retornara error
                                                         error(501, "Ningun nodo ha finalizado la solicitud, pero la solicitud es valida... no eres tu, soy yo");

        break;



   	case "downloadWeb":
    //cuando se descarga


                     //llamar servidor google photos tipo templink usercontent 
   	                 if($streamInfo->result->photo){
   	                 	include_once('photo.php');
   	                 }


                     //llamar servidor
   	                 if($streamInfo->result->cloud){
   	                 	include_once('cloud.php');
   	                 }



                     //llamar servidor chomikuj
   	                 if($streamInfo->result->copy){
   	                 	include_once('copy.php');
   	                 }


                     //llamar servidor minhateca
   	                 if($streamInfo->result->minh){
   	                 	include_once('minh.php');
   	                 }



                     //llamar servidor amazon
   	                 if($streamInfo->result->achede){
   	                 	include_once('achede.php');
   	                 }

                     //llamar servidor google drive
   	                 if($streamInfo->result->nora){
   	                 	include_once('nora.php');
   	                 }


                     //llamar servidor google photos tipo templink googlevideo
   	                 if($streamInfo->result->rin){
   	                 	include_once('rin.php');
   	                 }



                                                         //ningun nodo finalizo la solicitud, por lo que se retornara error
                                                         error(501, "Ningun nodo ha finalizado la solicitud, pero la solicitud es valida... no eres tu, soy yo");

        break;


                default:

                                                         //el callback no esta implementado
                                                         error(501, "El callback no tiene procedimientos...");



   }