<?php

// ES NECESARIO INSTALAR CURL
/* ajustes para todo el sitio  basicos */
define('HTTP_PROTOCOL', 'http');
define('SITE_NAME','Anime Móvil'); //nombre del sitio web
define('STREAM_PATH','/stream/'); //path del stream
define('API_PATH', '/api/'); //api path
define('DOMAIN', 'animemovil.com');
define('STATIC_DOMAIN', 'media.animemovil.com');

//base de datos
define("HOST", "localhost");     // El alojamiento al que deseas conectarte
define("USER", "root");    // El nombre de usuario de la base de datos
define("PASSWORD", "HUAWEIu8185");    // La contraseña de la base de datos
define("DATABASE", "animemovil");    // El nombre de la base de datos

//claves de acceso
define("KEYAPI", "b62G8GbE8wWkMUwN8177ugjemiHIkf7e1A");    // api key access
define("SIGNATURE_HASH", "bp2GGbE8wWkMU1wN8_DAIbQRmZkGTxyMmiHIkf+7e1A="); //hash para firmas de descargas
define("SIGNATURE_HASH_USER", "R7KbpdZX6i/HwTQwRv/Pl1_V61vMsBfimVWlp/Ny8ooA="); //hash para firmas de session

//paths
define("PATH_SYSTEM", "/var/www/html/"); //system path
define("CACHE_PATH", "/var/www/html/assets/cache/"); //cache path


//headers
header_remove(); //borrando headers
header('cache-control: max-age=0, no-cache'); //eliminar la cache
